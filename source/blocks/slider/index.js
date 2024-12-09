import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { assign } from 'lodash';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'SliderBlockSave' }),
});
