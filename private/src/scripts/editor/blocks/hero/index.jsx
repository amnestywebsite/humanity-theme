import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/hero', {
  // translators: [admin]
  title: __('Hero', 'amnesty'),
  icon: 'format-image',
  category: 'amnesty-core',
  supports: {
    align: true,
  },
  keywords: [
    // translators: [admin]
    __('Hero', 'amnesty'),
    // translators: [admin]
    __('Header', 'amnesty'),
    // translators: [admin]
    __('Banner', 'amnesty'),
  ],
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
    background: {
      type: 'string',
      default: 'dark',
    },
    hideImageCaption: {
      type: 'boolean',
      default: true,
    },
    hideImageCredit: {
      type: 'boolean',
      default: false,
    },
    type: {
      type: 'string',
      default: 'image',
    },
    featuredVideoId: {
      type: 'number',
    },
    featuredImageId: {
      type: 'string',
    },
    imageID: {
      type: 'number',
      default: 0
    },
    imageURL: {
      type: 'string'
    }
  },

  edit: DisplayComponent,

  save() {
    return <InnerBlocks.Content />;
  }
});
