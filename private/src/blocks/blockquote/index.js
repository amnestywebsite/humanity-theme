import './style.scss';
import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';
import transforms from './transforms.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  transforms,
  edit,
  save: () => null,
});
