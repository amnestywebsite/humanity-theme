import { getBlockTypes, unregisterBlockType } from '@wordpress/blocks';
import { select } from '@wordpress/data';

const allowedBlocks = [
  'core/button',
  'core/buttons',
  'core/column',
  'core/columns',
  'core/heading',
  'core/paragraph',
  'amnesty-core/action-block',
  'amnesty-core/block-list',
  'amnesty-core/block-section',
];

wp.domReady(() => {
  const postType = select('core/editor').getEditedPostAttribute('type');
  if (postType !== 'pop-in') {
    return;
  }

  getBlockTypes().forEach((type) => {
    if (!allowedBlocks.includes(type.name)) {
      unregisterBlockType(type.name);
    }
  });
});
