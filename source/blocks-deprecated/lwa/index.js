import edit from './edit.js';
import metadata from './block.json';
import BlockSave from './InnerSaveComponent.jsx';
import deprecated from './deprecated.jsx';


import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: BlockSave,
});
