import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/archive-filters', {
  title: __('Archive Filters', 'amnesty'),
  edit: DisplayComponent,
  save: () => null,
});
