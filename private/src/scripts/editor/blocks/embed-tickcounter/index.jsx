import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/embed-tickcounter', {
  // translators: [admin]
  title: __('Tickcounter Embed', 'amnesty'),
  icon: 'embed-photo',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Tickcounter', 'amnesty'),
    // translators: [admin]
    __('Embed', 'amnesty'),
    // translators: [admin]
    __('Counter', 'amnesty'),
  ],
  attributes: {
    alignment: {
      type: 'string',
      default: 'center',
    },
    source: {
      type: 'string',
      default: '',
    },
  },

  edit: DisplayComponent,

  save: () => null,
});
