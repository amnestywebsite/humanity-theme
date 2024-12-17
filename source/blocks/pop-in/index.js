import edit from './edit';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';
import { assign } from 'lodash';

const { InnerBlocks } = wp.blockEditor;

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'PopInBlockSave' }),
});
