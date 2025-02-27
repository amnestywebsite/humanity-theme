import './style.scss';

import { assign } from 'lodash';
import { InnerBlocks } from '@wordpress/block-editor';
import { createBlock, registerBlockType } from '@wordpress/blocks';

import edit from './edit.jsx';
import metadata from './block.json';

registerBlockType(metadata, {
  ...metadata,
  edit,
  save: assign(() => <InnerBlocks.Content />, { displayName: 'columnsBlock' }),
});

const findBlockType = (blocks, type) => {
  let found = [];

  // eslint-disable-next-line no-restricted-syntax
  for (const block of blocks) {
    if (block.name === type) {
      found.push(block.clientId);
    }

    if (block.innerBlocks.length) {
      found = [...found, ...findBlockType(block.innerBlocks, type)];
    }
  }

  return found;
};

wp.domReady(() => {
  const select = wp.data.select('core/block-editor');
  const dispatch = wp.data.dispatch('core/block-editor');
  const allBlocks = select.getBlocks();
  const columnRows = findBlockType(allBlocks, 'amnesty-core/block-row');

  select.getBlocksByClientId(columnRows).forEach((row) => {
    const rowColumns = findBlockType(row.innerBlocks, 'amnesty-core/block-row-column');
    const newColumns = select
      .getBlocksByClientId(rowColumns)
      .map((column) => createBlock('core/column', {}, column.innerBlocks));

    dispatch.replaceBlocks([row.clientId], [createBlock('core/columns', {}, newColumns)]);
  });
});
