import DisplayComponent from './DisplayComponent.jsx';

const { createBlock, registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/header', {
  // translators: [admin]
  title: __('Banner', 'amnesty'),
  icon: 'format-image',
  category: 'amnesty-core',
  // translators: [admin]
  keywords: [__('Banner', 'amnesty')],
  supports: {
    className: false,
    inserter: false,
  },
  attributes: {
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    ctaLink: {
      type: 'string',
    },
    ctaText: {
      type: 'string',
    },
    alignment: {
      type: 'string',
    },
    background: {
      type: 'string',
    },
    hideImageCaption: {
      type: 'boolean',
      default: true,
    },
    hideImageCopyright: {
      type: 'boolean',
      default: false,
    },
    size: {
      type: 'string',
    },
    type: {
      type: 'string',
    },
    embed: {
      type: 'string',
    },
    imageID: {
      type: 'integer',
    },
    imageURL: {
      type: 'string',
    },
    featuredVideoId: {
      type: 'integer',
    },
  },

  transforms: {
    from: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/block-hero'],
        transform: (attributes) => createBlock('amnesty-core/header', attributes),
      },
    ],
    to: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/block-hero'],
        transform: (attributes) => createBlock('amnesty-core/block-hero', attributes),
      },
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/hero'],
        transform: (attributes) => createBlock('amnesty-core/hero', attributes),
      },
    ],
  },

  edit: DisplayComponent,

  save: () => null,
});
