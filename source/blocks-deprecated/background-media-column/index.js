import edit from './edit';
import metadata from './block.json';
import deprecated from './deprecated';

import { registerBlockType } from '@wordpress/blocks';

const { assign } = lodash;
const { InnerBlocks } = wp.blockEditor;

registerBlockType(metadata, {
  ...metadata,
  deprecated,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'BackgroundMediaColumnBlockSave' }),
});
