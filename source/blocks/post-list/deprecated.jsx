const v1 = {
  attributes: {
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
  },
  save: () => null,
  migrate(attributes) {
    if (!attributes.category) {
      return attributes;
    }

    let { category } = attributes;

    category = JSON.stringify([JSON.parse(category)]);

    return assign({}, attributes, { category });
  },
};

const deprecated = [v1];

export default deprecated;
