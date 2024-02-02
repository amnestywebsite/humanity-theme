const { unregisterBlockType } = wp.blocks;
const { select } = wp.data;

wp.domReady(() => {
  const currentUserRole = window.userRoles[0];

  const allBlocks = select('core/blocks').getBlockTypes();

  if (currentUserRole === 'administrator') {
    return;
  }

  if (currentUserRole !== 'administrator') {
    const blocksToDisable = ['core/code', 'core/html'];

    allBlocks.forEach((block) => {
      // remove specifically disallowed blocks
      if (blocksToDisable.indexOf(block.name) !== -1) {
        unregisterBlockType(block.name);
      }
    });
  }

  if (currentUserRole !== 'editor') {
    const blocksToDisable = [
      'amnesty-core/image-block',
      'amnesty-core/links-with-icons',
      'amnesty-core/background-media',
      'amnesty-core/countdown-timer',
      'amnesty-core/term-list',
      'amnesty-core/block-recipients',
      'amnesty-core/repeatable-block',
      'amnesty-core/block-list',
      'amnesty-core/block-menu',
      'amnesty-core/regions',
      'amnesty-core/link-group',
      'amnesty-core/block-slider',
      'amnesty-wc/donation',
      'core/rss',
      'core/social-link',
      'core/social-links',
      'core/latest-posts',
      'core/shortcode',
      'core/archives',
      'core/categories',
      'core/quote',
      'core/search',
      'core/code',
      'code/html',
    ];

    allBlocks.forEach((block) => {
      // remove all yoast blocks
      if (block.name.indexOf('yoast-seo/') === 0) {
        unregisterBlockType(block.name);
        return;
      }

      // remove all woocomerce blocks
      if (block.name.indexOf('woocommerce/') === 0) {
        unregisterBlockType(block.name);
        return;
      }

      // remove specifically disallowed blocks
      if (blocksToDisable.indexOf(block.name) !== -1) {
        unregisterBlockType(block.name);
      }
    });
  }
});
