import { get } from 'lodash';
import { useCallback, useEffect, useState } from '@wordpress/element';

import SelectPreview from './SelectPreview.jsx';
import PostSelect from './post-selector/PostSelector.jsx';
import * as api from './post-selector/api';

/**
 * Returns a unique array of objects based on a desired key.
 * @param {array} arr - array of objects.
 * @param {string|int} key - key to filter objects by
 */
export const uniqueBy = (arr, key) => {
  const keys = [];
  return arr.filter((item) => {
    if (keys.indexOf(item[key]) !== -1) {
      return false;
    }
    keys.push(item[key]);
    return true;
  });
};

/**
 * Returns a unique array of objects based on the id property.
 * @param {array} arr - array of objects to filter.
 * @returns {*}
 */
export const uniqueById = (arr) => uniqueBy(arr, 'id');

// strip HTML from a string
const strip = (html) => {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || '';
};

/**
 * PostSelector Component
 */
const DisplaySelect = ({
  preview,
  selectedPosts,
  setAttributes,
  overrideTypes,
  style,
  prefix,
  showAuthor,
  showPostDate,
}) => {
  // State variables
  const [state, setState] = useState({
    filter: '',
    filterLoading: false,
    filterPosts: [],
    initialLoading: false,
    loading: false,
    pages: {},
    pagesTotal: {},
    paging: false,
    posts: [],
    type: 'post',
    types: {},
    taxonomies: [],
    taxonomy: '',
    terms: [],
    term: '',
  });

  const updateSelectedPosts = (posts) => {
    const uniq = [...new Set(posts)];
    setAttributes({ selectedPosts: uniq });
  };

  // Fetch posts based on current state
  const getPosts = useCallback(
    (args = {}) => {
      const pageKey = state.filter ? false : state.type;
      const defaultArgs = {
        per_page: 10,
        type: state.type,
        search: state.filter,
        page: state.pages[pageKey] || 1,
        show_hidden: true,
      };

      const requestArguments = {
        ...defaultArgs,
        ...args,
      };

      Object.keys(state.types).filter((key) => {
        if (key === state.type) {
          requestArguments.restBase = state.types[key].rest_base;
        }
        return state.types;
      });

      return api
        .getPosts(requestArguments, state.taxonomy, state.term)
        .then((data, i, xhr) => {
          const posts = Array.from(data).map((p) => {
            if (!p.featured_media || p.featured_media < 1) {
              return {
                ...p,
                featured_image: false,
              };
            }

            return {
              ...p,
              featured_image:
                get(
                  p,
                  '_embedded["wp:featuredmedia"][0].media_details.sizes["logomark@2x"].source_url',
                ) || get(p, '_embedded["wp:featuredmedia"][0].source_url', false),
            };
          });

          return {
            xhr,
            data: posts,
          };
        })
        .then(({ data = [], xhr }) => {
          if (requestArguments.search) {
            setState({
              filterPosts:
                requestArguments.page > 1 ? uniqueById([...state.filterPosts, ...data]) : data,
              pages: {
                ...state.pages,
                filter: requestArguments.page,
              },
              pagesTotal: {
                ...state.pagesTotal,
                filter: xhr.getResponseHeader('x-wp-totalpages'),
              },
            });

            return { data, xhr };
          }

          setState({
            // was posts: data but causes issue with pagination on the post list,
            posts: uniqueById([...state.posts, ...data]),
            pages: {
              ...state.pages,
              [pageKey]: requestArguments.page,
            },
            pagesTotal: {
              ...state.pagesTotal,
              [pageKey]: xhr.getResponseHeader('x-wp-totalpages'),
            },
          });

          // return response to continue the chain
          return { data, xhr };
        });
    },
    [
      state.filter,
      state.filterPosts,
      state.pages,
      state.pagesTotal,
      state.posts,
      state.taxonomy,
      state.term,
      state.type,
      state.types,
    ],
  );

  // Fetch selected posts by ID
  const retrieveSelectedPosts = useCallback(() => {
    if (selectedPosts.length === 0) {
      return Promise.resolve();
    }

    return Promise.all(
      Object.keys(state.types).map((type) =>
        getPosts({
          include: selectedPosts.join(','),
          per_page: 100,
          type,
        }),
      ),
    );
  }, [selectedPosts, state.types, getPosts]);

  // Other handler functions for adding, removing, filtering, etc.
  const addPost = (postId) => {
    if (state.filter) {
      const post = state.filterPosts.filter((p) => p.id === postId);
      const posts = uniqueById([...state.posts, ...post]);

      setState((prevState) => ({
        ...prevState,
        posts,
      }));
    }
    updateSelectedPosts([...selectedPosts, postId]);
  };

  const removePost = (postId) => {
    updateSelectedPosts(selectedPosts.filter((id) => id !== postId));
  };

  const reorderPosts = (posts) => {
    const items = Array.from(selectedPosts);
    const [reorderedItem] = items.splice(posts.source.index, 1);
    items.splice(posts.destination.index, 0, reorderedItem);

    updateSelectedPosts(items);
  };

  const doPostFilter = async () => {
    const { filter } = state;
    if (!filter) return;

    setState({ filtering: true, filterLoading: true });
    await getPosts();
    setState({ filterLoading: false });
  };

  /**
   * Handles the pagination of post types.
   */
  const doPagination = () => {
    setState({ paging: true });

    const pageKey = state.filter ? 'filter' : state.type;
    const page = parseInt(state.pages[pageKey], 10) + 1 || 2;

    getPosts({ page }).then(() => setState({ paging: false }));
  };

  /**
   * Gets the selected posts by id from the `posts` state object and
   * sorts them by their position in the selected array.
   *
   * @returns Array of objects.
   */
  const getSelectedPosts = () => {
    const items = state.posts
      .filter(({ id }) => selectedPosts.indexOf(id) !== -1)
      .sort((a, b) => {
        const aIndex = selectedPosts.indexOf(a.id);
        const bIndex = selectedPosts.indexOf(b.id);

        if (aIndex > bIndex) {
          return 1;
        }

        if (aIndex < bIndex) {
          return -1;
        }

        return 0;
      });

    return items;
  };

  const getPreviewPosts = () => {
    const selectedPostsData = getSelectedPosts();
    if (!selectedPostsData) return [];

    return selectedPostsData.map((resp) => {
      // eslint-disable-next-line no-underscore-dangle
      const tags = (resp._embedded['wp:term'] || []).flat().map((tag) => ({
        title: tag.name,
        link: tag.link,
      }));

      const featuredImage = resp.featured_media
        ? get(
            resp,
            '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url',
          ) || get(resp, '_embedded["wp:featuredmedia"][0].source_url', false)
        : false;

      let excerpt = strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

      return {
        id: resp.id,
        title: resp.title.rendered,
        link: resp.link,
        tag: tags.shift(),
        excerpt,
        featured_image: featuredImage,
        authorName: resp.authorName,
        date: resp.datePosted,
      };
    });
  };

  const handlePostTypeChange = ({ target: { value: type } }) => {
    setState((prevState) => ({ ...prevState, type, loading: true }));
    getPosts().then(() => setState((prevState) => ({ ...prevState, loading: false })));
  };

  /**
   * Event handler for when the post type select box changes in value.
   * @param string type - comes from the event object target.
   */
  const handleTaxonomyChange = ({ target: { value: taxonomy = '' } = {} } = {}) => {
    const allTerms = api.getTerms(taxonomy);

    allTerms
      .then((data) => {
        setState({ terms: data });
      })
      .then(() => {
        setState({ taxonomy, loading: true }, () => {
          getPosts().then(() => setState({ loading: false }));
        });
      });
  };

  const handleTermChange = ({ target: { value: term = '' } = {} } = {}) => {
    setState({ term, loading: true }, () => {
      getPosts().then(() => setState({ loading: false }));
    });
  };

  const handleInputFilterChange = ({ target: { value: filter } }) => {
    setState({ filter }, () => {
      if (filter) {
        doPostFilter();
      } else {
        setState((prevState) => ({ ...prevState, filterPosts: [], filtering: false }));
      }
    });
  };

  // Effect for fetching post types and initial posts on mount
  useEffect(() => {
    setState((prevState) => ({
      ...prevState,
      loading: true,
      initialLoading: true,
    }));

    const fetchPostTypes = async () => {
      let defaultType;
      if (overrideTypes) {
        defaultType = Object.keys(overrideTypes)[0] || 'post';
        setState((prevState) => ({
          ...prevState,
          types: overrideTypes,
          type: defaultType,
        }));
      } else {
        const data = await api.getPostTypes();
        const types = { ...data };
        delete types.attachment;
        delete types.wp_block;
        delete types.sidebar;

        setState((prevState) => ({
          ...prevState,
          types,
          type: 'post',
        }));
      }

      await retrieveSelectedPosts();
      await getPosts();
      setState((prevState) => ({
        ...prevState,
        loading: false,
        initialLoading: false,
      }));
    };

    fetchPostTypes();

    // Fetch taxonomies for filtering
    api.getTaxonomies().then((items) => {
      setState((prevState) => ({
        ...prevState,
        taxonomies: items,
      }));
    });
  }, [overrideTypes, retrieveSelectedPosts, getPosts]);

  return (
    <div>
      {preview && (
        <SelectPreview
          posts={getPreviewPosts()}
          loading={state.initialLoading}
          style={style}
          prefix={prefix}
          showAuthor={showAuthor}
          showPostDate={showPostDate}
        />
      )}
      {!preview && (
        <PostSelect
          state={state}
          handleInputFilterChange={handleInputFilterChange}
          handlePostTypeChange={handlePostTypeChange}
          getSelectedPosts={getSelectedPosts}
          removePost={removePost}
          addPost={addPost}
          doPagination={doPagination}
          handleTaxonomyChange={handleTaxonomyChange}
          handleTermChange={handleTermChange}
          reorderPosts={reorderPosts}
        />
      )}
    </div>
  );
};

export default DisplaySelect;
