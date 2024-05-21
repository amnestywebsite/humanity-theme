import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';
import transforms from './transforms';

const { registerBlockType } = wp.blocks;

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  transforms,
  edit,
  save: () => null,
});
