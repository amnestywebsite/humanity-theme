/* eslint-disable import/prefer-default-export */

/**
 * Recursively search blocks on page for a specific block type
 *
 * @param {Array} blocks the list of blocks to search
 * @param {String} type the block type to find
 *
 * @returns {Array}
 */
export function findBlockType(blocks, type) {
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
}
