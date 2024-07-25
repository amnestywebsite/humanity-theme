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
    className: true,
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
    background: {
      type: 'string',
      default: 'dark',
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
    featuredVideoId: {
      type: 'number',
    },
    hideImageCaption: {
      type: 'boolean',
      default: true,
    },
    hideImageCopyright: {
      type: 'boolean',
      default: false,
    },
    imageID: {
      type: 'number',
      default: 0,
    },
    title: {
      type: 'string',
    },
    type: {
      type: 'string',
      default: 'image',
    },
  },
  usesContext: ['postId', 'postType'],

  edit: DisplayComponent,

  save() {
    return <InnerBlocks.Content />;
  },
});
