import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/term-list', {
  // translators: [admin]
  title: __('Term A-Z', 'amnesty'),
  // translators: [admin]
  description: __('List all visible terms in a taxonomy, grouped by first letter', 'amnesty'),
  keywords: [
    // translators: [admin]
    __('Term List', 'amnesty'),
    // translators: [admin]
    __('A-Z Terms', 'amnesty'),
    // translators: [admin]
    __('Countries', 'amnesty'),
  ],
  icon: 'editor-textcolor',
  category: 'amnesty-core',

  attributes: {
    title: {
      type: 'string',
      // translators: [admin]
      default: __('A-Z of Countries and Regions', 'amnesty'),
    },
    taxonomy: {
      type: 'string',
    },
    alignment: {
      type: 'string',
    },
  },

  edit: DisplayComponent,

  save: () => null,
});
