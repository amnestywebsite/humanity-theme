import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/embed-flourish', {
  // translators: [admin]
  title: __('Flourish Embed', 'amnesty'),
  icon: 'embed-photo',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Flourish', 'amnesty'),
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
