import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/site-header', {
  edit: DisplayComponent,
  save: () => null,
});
