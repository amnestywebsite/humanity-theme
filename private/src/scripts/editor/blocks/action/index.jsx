import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { assign, omit } = lodash;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const blockAttributes = {
  style: {
    type: 'string',
  },
  centred: {
    type: 'boolean',
  },
  label: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  imageID: {
    type: 'integer',
  },
  imageURL: {
    type: 'string',
  },
  imageAlt: {
    type: 'string',
  },
  link: {
    type: 'string',
  },
  buttonBackground: {
    type: 'string',
  },
};

registerBlockType('amnesty-core/action-block', {
  // translators: [admin]
  title: __('Action', 'amnesty'),
  // translators: [admin]
  description: __('Add an Action block', 'amnesty'),
  icon: 'megaphone',
  category: 'amnesty-core',
  supports: {
    className: false,
    align: true,
  },
  attributes: assign({}, omit(blockAttributes, 'buttonBackground'), {
    linkText: {
      type: 'string',
    },
    largeImageURL: {
      type: 'string',
    },
  }),

  deprecated,
  edit: DisplayComponent,
  save: () => null,
});
