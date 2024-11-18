import edit from './edit';

import { registerBlockType } from '@wordpress/blocks';

registerBlockType('amnesty-core/sidebar', {
  edit,
  save: () => null,
});
