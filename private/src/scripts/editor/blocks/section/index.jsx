import DisplayComponent from './DisplayComponent.jsx';
import deprecated from './deprecated.jsx';

const { assign } = lodash;
const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-section', {
  // translators: [admin]
  title: __('Section', 'amnesty'),
  icon: 'editor-table',
  category: 'amnesty-core',
  // translators: [admin]
  keywords: [__('Section', 'amnesty')],
  attributes: {
    background: {
      type: 'string',
    },
    sectionId: {
      type: 'string',
    },
    sectionName: {
      type: 'string',
    },
    backgroundImage: {
      type: 'string',
      default: '',
    },
    backgroundImageHeight: {
      type: 'number',
      default: 0,
    },
    backgroundImageOrigin: {
      type: 'string',
      default: '',
    },
    enableBackgroundGradient: {
      type: 'boolean',
      default: false,
    },
    minHeight: {
      type: 'number',
      default: 0,
    },
    textColour: {
      type: 'string',
      default: 'black',
    },
    backgroundImageId: {
      type: 'number',
      default: 0,
    },
    hideImageCaption: {
      type: 'boolean',
      default: true,
    },
    hideImageCopyright: {
      type: 'boolean',
      default: false,
    },
  },

  deprecated,

  edit: DisplayComponent,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'SectionBlockSave' }),
});
