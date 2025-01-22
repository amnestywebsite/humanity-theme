import './style.scss';
import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';

import './replaceHeaders';

import edit from './edit.jsx';
import metadata from './block.json';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: () => null,
});
