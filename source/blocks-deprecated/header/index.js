import './style.scss';
import './editor.scss';

import edit from './edit';
import metadata from './block.json';

import { registerBlockType } from '@wordpress/blocks';

const { assign } = lodash;
const { InnerBlocks } = wp.blockEditor;

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'HeaderBlockSave' }),

  transforms: {
    from: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/header'],
        transform: (attributes) => createBlock('amnesty-core/block-hero', attributes),
      },
    ],
    to: [
      {
        type: 'block',
        isMultiBlock: false,
        blocks: ['amnesty-core/header'],
        transform: (attributes) => createBlock('amnesty-core/header', attributes),
      },
      {
        type: 'block',
        isMultiBlock: true,
        blocks: ['amnesty-core/hero'],
        transform: (attributes) => createBlock('amnesty-core/hero', attributes),
      },
    ],
  },
});

