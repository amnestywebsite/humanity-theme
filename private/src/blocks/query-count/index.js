import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';

registerBlockType(metadata, {
  ...metadata,
  edit: () => null,
  save: () => null,
});
