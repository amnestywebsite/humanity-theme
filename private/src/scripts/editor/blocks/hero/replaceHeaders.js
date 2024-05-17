import { findBlockType } from '../../utils';

const { omit } = lodash;
const { createBlock } = wp.blocks;

const metaKeyToAttributeMap = {
  _hero_alignment: 'align',
  _hero_background: 'background',
  _hero_content: 'content',
  _hero_cta_link: 'ctaLink',
  _hero_cta_text: 'ctaText',
  _hero_hide_image_caption: 'hideImageCaption',
  _hero_hide_image_copyright: 'hideImageCopyright',
  _hero_title: 'title',
  _hero_video_id: 'featuredVideoId',
};

/**
 * Retrieve hero block attributes from post meta
 *
 * @param {Boolean} isHeaderBlock whether the blocks is an header block
 *
 * @returns {Object}
 */
const getOldHeroBlockAttributes = (isHeaderBlock) => {
  const featuredImageId = wp.data.select('core/editor').getEditedPostAttribute('featured_media');
  const postMeta = wp.data.select('core/editor').getEditedPostAttribute('meta');

  const attributes = {
    featuredImageId,
    type: 'image',
  };

  if (isHeaderBlock) {
    // default type from attr is '', so keep out of meta map
    // eslint-disable-next-line no-underscore-dangle
    if (postMeta?._hero_type === 'video') {
      attributes.type = 'video';
    }

    Object.keys(postMeta)
      .filter((key) => key.indexOf('_hero') === 0)
      .forEach((key) => {
        const newKey = metaKeyToAttributeMap[key];
        if (newKey) {
          attributes[newKey] = postMeta[key];
        }
      });
  }

  return attributes;
};

window.addEventListener('load', () => {
  const { getBlocks, getBlocksByClientId } = wp.data.select('core/block-editor');
  const { replaceBlocks } = wp.data.dispatch('core/block-editor');

  // Get all blocks on a post
  const allBlocks = getBlocks();
  // Find all header blocks
  const headerBlocks = findBlockType(allBlocks, 'amnesty-core/block-hero');
  // Find all banner blocks
  const bannerBlocks = findBlockType(allBlocks, 'amnesty-core/header');

  // Spread header and banner blocks into one array
  const blocks = [...headerBlocks, ...bannerBlocks];

  if (!blocks.length) {
    return;
  }

  // For each header and banner block, replace it with a hero block
  getBlocksByClientId(blocks).forEach((block) => {
    // Create new attributes for the hero block
    const newAttributes = {
      ...omit(block.attributes, ['alignment', 'background']),
      align: block.attributes.alignment,
      ...getOldHeroBlockAttributes(block.name === 'amnesty-core/block-hero'),
      background: block.attributes.background || 'dark',
    };

    // Replace header block with hero block
    replaceBlocks(
      [block.clientId],
      [createBlock('amnesty-core/hero', newAttributes, block.innerBlocks)],
    );
  });
});
