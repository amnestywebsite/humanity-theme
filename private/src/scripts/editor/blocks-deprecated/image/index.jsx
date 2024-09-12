import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const blockAttributes = {
  type: {
    type: 'string',
  },
  style: {
    type: 'string',
  },
  align: {
    type: 'string',
  },
  hasOverlay: {
    type: 'boolean',
  },
  parallax: {
    type: 'boolean',
  },
  identifier: {
    type: 'string',
    source: 'attribute',
    selector: '.imageBlock',
    attribute: 'class',
  },

  imageID: {
    type: 'integer',
  },
  imageURL: {
    type: 'string',
  },

  videoID: {
    type: 'integer',
  },
  videoURL: {
    type: 'string',
  },

  title: {
    type: 'string',
  },
  content: {
    type: 'string',
  },
  buttons: {
    type: 'array',
    default: [],
  },
  caption: {
    type: 'string',
  },
};

registerBlockType('amnesty-core/image-block', {
  // translators: [admin]
  title: __('Image Block', 'amnesty'),
  // translators: [admin]
  description: __('Add a flexible image block with optional overlay', 'amnesty'),
  icon: 'format-image',
  category: 'amnesty-core',
  supports: {
    className: false,
    inserter: false,
  },
  attributes: blockAttributes,

  deprecated,

  edit: DisplayComponent,
  save: () => null,
});
