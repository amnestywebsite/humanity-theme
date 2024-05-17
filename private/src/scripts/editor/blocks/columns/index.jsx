import classNames from 'classnames';

import { findBlockType } from '../../utils';
import './row-column.jsx';
import DisplayComponent from './DisplayComponent.jsx';

const { InnerBlocks } = wp.blockEditor;
const { createBlock, registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('amnesty-core/block-row', {
  // translators: [admin]
  title: __('Columns', 'amnesty'),
  icon: 'editor-insertmore',
  category: 'amnesty-core',
  parent: ['amnesty-core/block-section'],
  keywords: [
    // translators: [admin]
    __('Content', 'amnesty'),
    // translators: [admin]
    __('Row', 'amnesty'),
    // translators: [admin]
    __('Columns', 'amnesty'),
  ],
  attributes: {
    layout: {
      type: 'string',
    },
  },
  supports: {
    inserter: false,
  },

  edit: DisplayComponent,

  save: Object.assign(
    ({ attributes }) => {
      const { layout = '1/2|1/2' } = attributes;

      return (
        <div className={classNames('row', { [`layout-${layout}`]: true })}>
          <InnerBlocks.Content />
        </div>
      );
    },
    { displayName: 'ColumnsBlockSave' },
  ),
});

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
