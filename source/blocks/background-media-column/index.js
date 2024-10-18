import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: () => null,
});
