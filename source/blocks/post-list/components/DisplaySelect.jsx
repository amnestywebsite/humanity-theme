import React, { useState, useEffect } from 'react';
import SelectPreview from '../0components/SelectPreview.jsx';
import PostSelect from './post-selector/PostSelector.jsx';
import * as api from './post-selector/api.js';
import lodash from 'lodash';

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

/**
 * Debounce a function by limiting how often it can run.
 * @param {function} func - callback function
 * @param {Integer} wait - Time in milliseconds how long it should wait.
 * @returns {Function}
 */
export const debounce = (func, wait) => {
  let timeout = null;

  return (...args) => {
    const context = this;
    const later = () => {
      func.apply(context, args);
    };

    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
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
  showPostDate
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

  }, [overrideTypes]); // Dependency on overrideTypes

  // Fetch posts based on current state
  const getPosts = async (args = {}) => {
    const { filter, type, pages, taxonomies } = state;
    const pageKey = filter ? false : type;
    const defaultArgs = {
      per_page: 10,
      type,
      search: filter,
      page: pages[pageKey] || 1,
      show_hidden: true,
    };

    const requestArguments = {
      ...defaultArgs,
      ...args,
    };

    if (state.types[type]) {
      requestArguments.restBase = state.types[type].rest_base;
    }

    const { data, xhr } = await api.getPosts(requestArguments, state.taxonomy, state.term);

    const posts = data.map((p) => ({
      ...p,
      featured_image: p.featured_media ?
        lodash.get(p, '_embedded["wp:featuredmedia"][0].media_details.sizes["logomark@2x"].source_url') ||
        lodash.get(p, '_embedded["wp:featuredmedia"][0].source_url', false)
        : false,
    }));

    if (requestArguments.search) {
      setState((prevState) => ({
        ...prevState,
        filterPosts: requestArguments.page > 1 ? uniqueById([...prevState.filterPosts, ...posts]) : posts,
        pages: {
          ...prevState.pages,
          filter: requestArguments.page,
        },
        pagesTotal: {
          ...prevState.pagesTotal,
          filter: xhr.getResponseHeader('x-wp-totalpages'),
        },
      }));
    } else {
      setState((prevState) => ({
        ...prevState,
        posts: uniqueById([...prevState.posts, ...posts]),
        pages: {
          ...prevState.pages,
          [pageKey]: requestArguments.page,
        },
        pagesTotal: {
          ...prevState.pagesTotal,
          [pageKey]: xhr.getResponseHeader('x-wp-totalpages'),
        },
      }));
    }

    return { data, xhr };
  };

  // Fetch selected posts by ID
  const retrieveSelectedPosts = async () => {
    if (selectedPosts.length === 0) {
      return Promise.resolve();
    }

    await Promise.all(
      Object.keys(state.types).map((type) =>
        getPosts({
          include: selectedPosts.join(','),
          per_page: 100,
          type,
        })
      )
    );
  };

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

  const updateSelectedPosts = (posts) => {
    const uniq = [...new Set(posts)];
    setAttributes({ selectedPosts: uniq });
  };

  const handlePostTypeChange = ({ target: { value: type } }) => {
    setState((prevState) => ({ ...prevState, type, loading: true }));
    getPosts().then(() => setState((prevState) => ({ ...prevState, loading: false })));
  };

  const handleInputFilterChange = ({ target: { value: filter } }) => {
    setState({ filter }, () => {
      if (!filter) {
        return setState((prevState) => ({ ...prevState, filterPosts: [], filtering: false }));
      }
      doPostFilter();
    });
  };

  const doPostFilter = async () => {
    const { filter } = state;
    if (!filter) return;

    setState({ filtering: true, filterLoading: true });
    await getPosts();
    setState({ filterLoading: false });
  };

  const getPreviewPosts = () => {
    const selectedPostsData = getSelectedPosts();
    if (!selectedPostsData) return [];

    return selectedPostsData.map((resp) => {
      const tags = (resp._embedded['wp:term'] || []).flat().map((tag) => ({
        title: tag.name,
        link: tag.link,
      }));

      const featuredImage = resp.featured_media ?
        lodash.get(resp, '_embedded["wp:featuredmedia"][0].media_details.sizes["post-half@2x"].source_url') ||
        lodash.get(resp, '_embedded["wp:featuredmedia"][0].source_url', false)
        : false;

      let excerpt = DisplaySelect.strip(resp.excerpt.rendered);
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

  // Static method to strip HTML from a string
  const strip = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

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
