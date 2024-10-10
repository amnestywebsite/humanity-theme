import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/sidebar', {
  edit: DisplayComponent,
  save: () => null,
});
