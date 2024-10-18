import './style.scss';
import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: () => null,
});
