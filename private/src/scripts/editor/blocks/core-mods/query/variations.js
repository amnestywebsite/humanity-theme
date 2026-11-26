const { __ } = wp.i18n;
const { registerBlockVariation } = wp.blocks;

const registerPostVariation = (slug, settings) =>
  registerBlockVariation('core/query', {
    name: `amnesty/${slug}`,
    category: 'amnesty-core',
    scope: ['inserter'],
    attributes: {
      namespace: `amnesty/${slug}`,
      query: {
        perPage: 3,
        pages: 0,
        offset: 0,
        postType: 'post',
        order: 'desc',
        orderBy: 'date',
        author: '',
        search: '',
        exclude: [],
        sticky: '',
        inherit: false,
      },
    },
    isActive: ['namespace'],
    ...settings,
  });

registerPostVariation('post-list', {
  title: __('Post List', 'amnesty'),
  description: __('A list of posts showing their category and title.', 'amnesty'),
  icon: 'list-view',
  innerBlocks: [
    [
      'core/post-template',
      { className: 'linkList' },
      [
        [
          'core/group',
          { tagName: 'article', className: 'linkList-item' },
          [
            ['core/post-terms', { term: 'category', className: 'linkList-itemMeta' }],
            ['core/post-title', { level: 3, isLink: true, className: 'linkList-itemTitle' }],
          ],
        ],
      ],
    ],
  ],
});

registerPostVariation('post-grid', {
  title: __('Post Grid', 'amnesty'),
  description: __('A grid of posts showing their featured image, category and title.', 'amnesty'),
  icon: 'grid-view',
  innerBlocks: [
    [
      'core/post-template',
      { className: 'grid grid-3' },
      [
        [
          'core/group',
          { tagName: 'article', className: 'grid-item' },
          [
            ['core/post-featured-image', { sizeSlug: 'post-half@2x' }],
            [
              'core/group',
              { className: 'grid-content' },
              [
                ['core/post-terms', { term: 'category', className: 'grid-itemMeta' }],
                ['core/post-title', { level: 3, isLink: true, className: 'grid-itemTitle' }],
              ],
            ],
          ],
        ],
      ],
    ],
  ],
});
