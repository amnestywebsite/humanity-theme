import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/related-content', {
  /* translators: [front] shown on post single */
  title: __('Related Content', 'amnesty'),
  // translators: [admin]
  description: __('Add a grid of posts that are related to the current post', 'amnesty'),
  category: 'amnesty-core',
  attributes: {},
  edit: DisplayComponent,
  save: () => null,
});
