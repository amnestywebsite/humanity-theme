import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';
import deprecated from './deprecated.jsx';

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'KeyFactsBlockSave' }),
});
