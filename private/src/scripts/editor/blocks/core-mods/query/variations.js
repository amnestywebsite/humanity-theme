const { __ } = wp.i18n;
const { registerBlockVariation } = wp.blocks;

const query = () => ({
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
});

registerBlockVariation('core/query', {
  name: 'amnesty/post-list',
  title: __('Post List', 'amnesty'),
  description: __('A list of posts showing their category and title.', 'amnesty'),
  icon: 'list-view',
  category: 'amnesty-core',
  scope: ['inserter'],
  attributes: { namespace: 'amnesty/post-list', query: query() },
  isActive: ['namespace'],
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
