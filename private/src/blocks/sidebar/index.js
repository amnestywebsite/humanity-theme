import './style.scss';

import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';

registerBlockType('amnesty-core/sidebar', {
  edit,
  save: () => null,
});
