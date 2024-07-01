const { assign } = lodash;
const { addFilter } = wp.hooks;

const unusedNamespaces = ['yoast', 'yoast-seo', 'woocommerce'];

const unusedBlocks = [
  'core/archives',
  'core/avatar',
  'core/calendar',
  'core/categories',
  'core/comments',
  'core/comments-pagination',
  'core/comment-content',
  'core/comment-edit-link',
  'core/comment-reply-link',
  'core/comment-template',
  'core/cover',
  'core/details',
  'core/file',
  'core/footnotes',
  'core/freeform',
  'core/gallery',
  'core/latest-comments',
  'core/loginout',
  'core/media-text',
  'core/more',
  'core/navigation',
  'core/nextpage',
  'core/page-list',
  'core/post-author',
  'core/post-author-biography',
  'core/post-author-name',
  'core/post-comments',
  'core/post-comments-form',
  'core/post-content',
  'core/post-date',
  'core/post-excerpt',
  'core/post-featured-image',
  'core/post-navigation-link',
  'core/post-template',
  'core/post-terms',
  'core/post-title',
  'core/preformatted',
  'core/pullquote',
  'core/query',
  'core/query-title',
  'core/read-more',
  'core/rss',
  'core/search',
  'core/site-logo',
  'core/site-tagline',
  'core/site-title',
  'core/tag-cloud',
  'core/term-description',
  'core/verse',
  'amnesty-core/image-block',
];

/**
 * Remove unused blocks.
 */
addFilter(
  'blocks.registerBlockType',
  'amnesty-core',
  (settings, name) => {
    if (unusedBlocks.includes(name)) {
      return assign({}, settings, {
        supports: assign({}, settings.supports, {
          inserter: false,
        }),
      });
    }

    const [namespace] = name.split('/');

    if (unusedNamespaces.includes(namespace)) {
      return assign({}, settings, {
        supports: assign({}, settings.supports, {
          inserter: false,
        }),
      });
    }

    return settings;
  },
  100,
);
