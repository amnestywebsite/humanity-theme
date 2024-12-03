import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

import { registerBlockType, registerBlockStyle } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { assign } from 'lodash';
import { __ } from '@wordpress/i18n';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'LinksWithIconsBlockSave' }),
});

registerBlockStyle('amnesty-core/repeatable-block', {
  name: 'square',
  // translators: [admin]
  label: __('Square', 'amnesty'),
});
