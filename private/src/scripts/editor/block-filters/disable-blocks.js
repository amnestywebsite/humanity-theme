const { unregisterBlockType } = wp.blocks;

const disabledBlocks = ['core/cover'];

wp.domReady(() => {
  disabledBlocks.forEach(unregisterBlockType);
});
