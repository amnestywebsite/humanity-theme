import './editor.scss';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';

import '../../store';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'SliderBlockSave' }),
});
