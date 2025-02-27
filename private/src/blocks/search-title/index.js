import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';

registerBlockType(metadata.name, {
  ...metadata,
  edit,
  save: () => null,
});
