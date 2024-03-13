const { omit } = lodash;
const { createBlock } = wp.blocks;

const findBlockType = (blocks, type) => {
  let found = [];

  Array.from(blocks).forEach((block) => {
    if (block.name === type) {
      found.push(block.clientId);
    }

    if (block.innerBlocks.length) {
      found = [...found, ...findBlockType(block.innerBlocks, type)];
    }
  });

  return found;
};

window.addEventListener('load', () => {
  const select = wp.data.select('core/block-editor');
  const dispatch = wp.data.dispatch('core/block-editor');
  // Get all blocks on a post
  const allBlocks = select.getBlocks();
  // Find all header blocks
  const headerBlocks = findBlockType(allBlocks, 'amnesty-core/block-hero');
  // Find all banner blocks
  const bannerBlocks = findBlockType(allBlocks, 'amnesty-core/header');

  // Spread header and banner blocks into one array
  const blocks = [...headerBlocks, ...bannerBlocks];

  // For each header and banner block, replace it with a hero block
  select.getBlocksByClientId(blocks).forEach((block) => {
    // Create new attributes for the hero block
    const newAttributes = {
      ...omit(block.attributes, ['alignment']),
      align: block.attributes.alignment,
    };

    // Replace header block with hero block
    dispatch.replaceBlocks(
      [block.clientId],
      [createBlock('amnesty-core/hero', newAttributes, block.innerBlocks)],
    );
  });
});
