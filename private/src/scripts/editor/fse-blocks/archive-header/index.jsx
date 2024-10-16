import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/archive-header', {
  edit: DisplayComponent,
  save: () => null,
});
