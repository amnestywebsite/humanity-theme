import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  edit: () => null,
  save: () => null,
});
