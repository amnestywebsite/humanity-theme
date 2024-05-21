import edit from './edit';
import metadata from './block.json';

const { registerBlockType } = wp.blocks;

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: () => null,
});
