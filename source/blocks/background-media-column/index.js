import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

const { registerBlockType } = wp.blocks;

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: () => null,
});
