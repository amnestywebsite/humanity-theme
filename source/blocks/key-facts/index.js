import './style.scss';
import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';
import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';

import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';


registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'KeyFactsBlockSave' }),
});
