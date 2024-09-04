import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/post-search', {
  edit: DisplayComponent,
  save: () => null,
});
