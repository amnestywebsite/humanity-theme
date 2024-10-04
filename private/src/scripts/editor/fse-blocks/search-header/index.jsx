import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/search-header', {
  edit: DisplayComponent,
  save: () => null,
});
