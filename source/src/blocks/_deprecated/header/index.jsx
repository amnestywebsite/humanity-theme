import './style.scss';
import './editor.scss';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { createBlock, registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';

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
