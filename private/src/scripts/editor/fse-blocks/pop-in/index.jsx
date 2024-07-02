import DisplayComponent from './DisplayComponent.jsx';

const { registerBlockType } = wp.blocks;

registerBlockType('amnesty-core/pop-in', {
  edit: DisplayComponent,
  save: () => null,
});
