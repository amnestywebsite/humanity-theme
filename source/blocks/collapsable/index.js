import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';

const { assign } = lodash;
const { InnerBlocks } = wp.blockEditor;
const { registerBlockType } = wp.blocks;

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'CollapsableBlockSave' }),
});
