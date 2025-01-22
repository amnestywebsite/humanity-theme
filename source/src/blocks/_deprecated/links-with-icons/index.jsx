import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType, registerBlockStyle } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'LinksWithIconsBlockSave' }),
});

registerBlockStyle('amnesty-core/repeatable-block', {
  name: 'square',
  /* translators: [admin] */
  label: __('Square', 'amnesty'),
});
