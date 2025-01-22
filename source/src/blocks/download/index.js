import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: () => null,
});
