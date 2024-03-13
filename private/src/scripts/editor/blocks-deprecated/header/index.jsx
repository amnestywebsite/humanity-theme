import DisplayComponent from './DisplayComponent.jsx';

const { createBlock, registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-hero', {
  // translators: [admin]
  title: __('Header', 'amnesty'),
  icon: 'format-image',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Header', 'amnesty'),
  ],
  supports: {
    multiple: false,
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
      default: 'dark',
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
      default: 'small',
    },
    type: {
      type: 'string',
    },
    embed: {
      type: 'string',
    },
    featuredVideoId: {
      type: 'number',
    },
  },

  transforms: {
    from: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/header'],
        transform: (attributes) => createBlock('amnesty-core/block-hero', attributes),
      },
    ],
    to: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/header'],
        transform: (attributes) => createBlock('amnesty-core/header', attributes),
      },
      {
        type: 'block',
        isMultiBlock: true,
        blocks: ['amnesty-core/hero'],
        transform: (attributes) => createBlock('amnesty-core/hero', attributes),
      },
    ],
  },

  edit: DisplayComponent,

  save() {
    return <InnerBlocks.Content />;
  },
});
