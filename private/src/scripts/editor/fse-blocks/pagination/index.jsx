import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/pagination', {
  edit: DisplayComponent,
  save: () => null,
});
