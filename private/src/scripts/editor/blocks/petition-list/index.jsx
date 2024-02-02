import PetitionEdit from './PetitionEdit.jsx';
import PostsWrapper from '../post-list/PostsWrapper.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const postListConfig = {
  style: 'petition',
};

const PetitionEditCompose = PostsWrapper(PetitionEdit, postListConfig);

const petitionSlug = window?.postTypes?.petition;

// the extra attributes are used by the post list HOC

const blockAttributes = {
  type: {
    type: 'string',
    default: 'category',
  },
  style: {
    type: 'string',
    default: 'petition',
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
  extraStyleOptions: {
    type: 'array',
    default: [
      {
        // translators: [admin]
        label: __('Petitions', 'amnesty'),
        value: 'petition',
      },
    ],
  },
  postTypes: {
    type: 'object',
    default: {
      [petitionSlug]: {
        // translators: [admin]
        name: __('Petitions', 'amnesty'),
        rest_base: petitionSlug,
      },
    },
  },
  displayTypes: {
    type: 'array',
    default: [
      {
        // translators: [admin]
        label: __('Object Selection', 'amnesty'),
        value: 'select',
      },
      {
        // translators: [admin]
        label: __('Feed', 'amnesty'),
        value: 'feed',
      },
    ],
  },
};

registerBlockType('amnesty-core/petition-list', {
  // translators: [admin]
  title: __('Petition List', 'amnesty'),
  // translators: [admin]
  description: __('Petition list', 'amnesty'),
  // translators: [admin]
  keywords: [__('Petition', 'amnesty')],
  category: 'amnesty-core',

  attributes: blockAttributes,

  edit: PetitionEditCompose,

  save: () => null,
});
