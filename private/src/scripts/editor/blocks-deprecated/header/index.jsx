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
      source: 'meta',
      meta: '_hero_title',
    },
    content: {
      type: 'string',
      source: 'meta',
      meta: '_hero_content',
    },
    ctaLink: {
      type: 'string',
      source: 'meta',
      meta: '_hero_cta_link',
    },
    ctaText: {
      type: 'string',
      source: 'meta',
      meta: '_hero_cta_text',
    },
    alignment: {
      type: 'string',
      source: 'meta',
      meta: '_hero_alignment',
    },
    background: {
      type: 'string',
      source: 'meta',
      meta: '_hero_background',
      default: 'dark',
    },
    hideImageCaption: {
      type: 'boolean',
      source: 'meta',
      meta: '_hero_hide_image_caption',
      default: true,
    },
    hideImageCopyright: {
      type: 'boolean',
      source: 'meta',
      meta: '_hero_hide_image_copyright',
      default: false,
    },
    size: {
      type: 'string',
      source: 'meta',
      meta: '_hero_size',
      default: 'small',
    },
    type: {
      type: 'string',
      source: 'meta',
      meta: '_hero_type',
    },
    embed: {
      type: 'string',
      source: 'meta',
      meta: '_hero_embed',
    },
    featuredVideoId: {
      type: 'string',
      source: 'meta',
      meta: '_hero_video_id',
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
