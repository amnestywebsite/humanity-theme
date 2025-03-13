import { getEntityRecords, getMedia, useEntityRecords } from '@wordpress/core-data';
import { useEffect, useState } from '@wordpress/element';

import SelectPreview from './SelectPreview.jsx';
import PostSelect from './post-selector/PostSelector.jsx';

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
  const { records: allPostTypes, isResolving: allPostTypesResolving } = useEntityRecords(
    'root',
    'postType',
  );

  const [postTypes, setPostTypes] = useState([]);
  useEffect(() => {
    if (Array.isArray(overrideTypes) && overrideTypes.length) {
      setPostTypes(overrideTypes);
      return;
    }

    if (allPostTypesResolving) {
      setPostTypes([]);
      return;
    }

    setPostTypes(allPostTypes?.filter((type) => !!type.viewable));
  }, [overrideTypes, allPostTypes, allPostTypesResolving]);

  const { records: taxonomies, isResolving: taxonomiesResolving } = useEntityRecords(
    'root',
    'taxonomy',
  );

  const [taxonomyFilter, setTaxonomyFilter] = useState(null);

  const { records: terms, isResolving: termsResolving } = useEntityRecords(
    'taxonomy',
    taxonomyFilter,
    { per_page: -1 },
  );

  const [termFilter, setTermFilter] = useState(null);
  const [pageNumber, setPageNumber] = useState(1);
  const [postType, setPostType] = useState('post');
  const [searchTerm, setSearchTerm] = useState('');

  const { records: availablePosts, isResolving: availablePostsResolving } = useEntityRecords(
    'postType',
    postType,
    {
      search: searchTerm,
      per_page: 10,
      page: pageNumber,
      [taxonomyFilter]: termFilter,
    },
  );

  const { records: currentPosts, isResolving: postsResolving } = useEntityRecords(
    'postType',
    postType,
    {
      include: selectedPosts,
    },
  );

  const isLoading =
    allPostTypesResolving || taxonomiesResolving || termsResolving || postsResolving;

  const updateSelectedPosts = (posts) => {
    const uniq = [...new Set(posts)];
    setAttributes({ selectedPosts: uniq });
  };

  const addPost = (postId) => {
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

  /**
   * Gets the selected posts by id from the `posts` state object and
   * sorts them by their position in the selected array.
   *
   * @returns Array of objects.
   */
  const getSelectedPosts = () => {
    if (postsResolving || !currentPosts) {
      return [];
    }

    const items = currentPosts
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

  const getPreviewPosts = async () => {
    const selectedPostsData = getSelectedPosts();
    if (!selectedPostsData) return [];

    return selectedPostsData.map(async (item) => {
      const topics = await getEntityRecords('taxonomy', taxonomyFilter, item[taxonomyFilter]);
      const tags = topics.map((topic) => ({
        title: topic.name,
        link: topic.link,
      }));

      const image = await getMedia(item.featured_media);
      const imageUrl = image?.media_details?.sizes?.thumbnail?.source_url;

      let excerpt = strip(item.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

      return {
        id: item.id,
        title: item.title.rendered,
        link: item.link,
        tag: tags.shift(),
        excerpt,
        featured_image: imageUrl,
        authorName: item.authorName,
        date: item.datePosted,
      };
    });
  };

  if (preview) {
    return (
      <div>
        <SelectPreview
          posts={getPreviewPosts()}
          loading={isLoading}
          style={style}
          prefix={prefix}
          showAuthor={showAuthor}
          showPostDate={showPostDate}
        />
      </div>
    );
  }

  const state = {
    filter: searchTerm,
    filterLoading: availablePostsResolving,
    filterPosts: availablePosts,
    filtering: !!searchTerm || !!termFilter,
    initialLoading: isLoading,
    loading: isLoading,
    pages: pageNumber,
    pagesTotal: null,
    paging: pageNumber > 1,
    posts: availablePosts || [],
    taxonomies,
    terms,
    type: postType,
    types: postTypes,
  };

  return (
    <div>
      <PostSelect
        state={state}
        handleInputFilterChange={setSearchTerm}
        handlePostTypeChange={setPostType}
        getSelectedPosts={getSelectedPosts}
        removePost={removePost}
        addPost={addPost}
        doPagination={() => setPageNumber(pageNumber + 1)}
        handleTaxonomyChange={setTaxonomyFilter}
        handleTermChange={setTermFilter}
        reorderPosts={reorderPosts}
      />
    </div>
  );
};

export default DisplaySelect;
