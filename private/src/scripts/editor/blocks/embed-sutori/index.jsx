import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/embed-sutori', {
  // translators: [admin]
  title: __('Sutori Embed', 'amnesty'),
  icon: 'embed-photo',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Sutori', 'amnesty'),
    // translators: [admin]
    __('Embed', 'amnesty'),
  ],
  attributes: {
    source: {
      type: 'string',
      default: '',
    },
  },

  edit: DisplayComponent,

  save: () => null,
});
