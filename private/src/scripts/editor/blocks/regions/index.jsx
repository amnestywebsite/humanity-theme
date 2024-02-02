import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/regions', {
  // translators: [admin]
  title: __('Terms List', 'amnesty'),
  // translators: [admin]
  keywords: [__('Terms', 'amnesty')],
  category: 'amnesty-core',

  attributes: {
    title: {
      type: 'string',
      default: '',
    },
    taxonomy: {
      type: 'string',
      default: '',
    },
    background: {
      type: 'string',
      default: '',
    },
    alignment: {
      type: 'string',
      default: '',
    },
    depth: {
      type: 'number',
      default: 1,
    },
    regionsOnly: {
      type: 'boolean',
      default: false,
    },
  },

  edit: DisplayComponent,

  save: () => null,
});
