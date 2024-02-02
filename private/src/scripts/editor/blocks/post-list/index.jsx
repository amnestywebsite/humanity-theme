import PostListEdit from './PostListEdit.jsx';
import PostsWrapper from './PostsWrapper.jsx';

const { assign } = lodash;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const postListConfig = {};

const PostsEditCompose = PostsWrapper(PostListEdit, postListConfig);

const blockAttributes = {
  type: {
    type: 'string',
    default: 'category',
  },
  style: {
    type: 'string',
    default: 'list',
  },
  category: {
    type: 'string',
  },
  categoryRelated: {
    type: 'boolean',
  },
  amount: {
    type: 'int',
  },
  custom: {
    type: 'array',
  },
  selectedPosts: {
    type: 'array',
  },
  taxonomyFilters: {
    type: 'array',
  },
  taxonomy: {
    type: 'object',
  },
  terms: {
    type: 'array',
    default: [],
  },
  authors: {
    type: 'string',
  },
  displayAuthor: {
    type: 'boolean',
    default: false,
  },
  displayPostDate: {
    type: 'boolean',
    default: false,
  },
};

registerBlockType('amnesty-core/block-list', {
  // translators: [admin]
  title: __('Post List', 'amnesty'),
  icon: 'admin-post',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('List', 'amnesty'),
    // translators: [admin]
    __('post-list', 'amnesty'),
    // translators: [admin]
    __('Posts', 'amnesty'),
  ],
  supports: {
    multiple: true,
  },
  attributes: blockAttributes,

  deprecated: [
    {
      attributes: blockAttributes,
      save: () => null,
      migrate(attributes) {
        if (!attributes.category) {
          return attributes;
        }

        let { category } = attributes;

        category = JSON.stringify([JSON.parse(category)]);

        return assign({}, attributes, { category });
      },
    },
  ],

  edit: PostsEditCompose,

  // Returns null due to the component being rendered server side
  save: () => null,
});
