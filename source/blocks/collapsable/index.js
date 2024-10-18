import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'CollapsableBlockSave' }),
});
