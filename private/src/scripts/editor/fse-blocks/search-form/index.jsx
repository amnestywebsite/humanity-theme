import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/search-form', {
  edit: DisplayComponent,
  save: () => null,
});
