import DisplayComponent from './DisplayComponent.jsx';
import metadata from './block.json';

const { registerBlockType } = wp.blocks;

registerBlockType(metadata.name, {
  ...metadata,
  edit: DisplayComponent,
  save: () => null,
});
