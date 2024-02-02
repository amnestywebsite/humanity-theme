import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/embed-infogram', {
  // translators: [admin]
  title: __('Infogram Embed', 'amnesty'),
  icon: 'embed-photo',
  category: 'amnesty-core',
  keywords: [
    // translators: [admin]
    __('Infogram', 'amnesty'),
    // translators: [admin]
    __('Embed', 'amnesty'),
  ],
  attributes: {
    identifier: {
      type: 'string',
      default: '',
    },
    type: {
      type: 'string',
      default: 'interactive',
    },
    title: {
      type: 'string',
      default: '',
    },
  },

  edit: DisplayComponent,

  save: () => null,
});
