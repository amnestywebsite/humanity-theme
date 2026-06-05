const { __ } = wp.i18n;

/**
 * Make the pagination widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/pagination/js/
 */
export default function PaginationWidget() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.querySelector('.post-pagination');

  if (!container) {
    return null;
  }

  return window.instantsearch.widgets.pagination({
    container,
    showFirst: false,
    showPrevious: true,
    showNext: true,
    showLast: false,
    scrollTo: '.search-results',
    cssClasses: {
      root: [
        'wp-block-group',
        'post-paginationContainer',
        'is-content-justification-space-between',
        'is-nowrap',
        'is-layout-flex',
        'wp-block-group-is-layout-flex',
      ],
      previousPageItem: [
        'wp-block-group',
        'post-paginationLink',
        'post-paginationPrevious',
        'is-layout-flow',
        'wp-block-group-is-layout-flow',
      ],
      nextPageItem: [
        'wp-block-group',
        'post-paginationLink',
        'post-paginationNext',
        'is-layout-flow',
        'wp-block-group-is-layout-flow',
      ],
      list: ['wp-block-list', 'page-numbers'],
      item: ['wp-block-list-item'],
      selectedItem: ['current'],
      link: ['page-numbers'],
    },
    templates: {
      previous({ page }, { html }) {
        const isDisabled = page === 0;
        const classes = `wp-block-button is-style-pagination ${isDisabled ? 'is-disabled' : ''}`;

        return html`
          <div class="wp-block-group post-paginationLink post-paginationPrevious is-layout-flow wp-block-group-is-layout-flow">
            <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
              <div class="${classes}">
                <a class="wp-block-button__link wp-element-button">
                  <span class="icon"></span>
                  <span>${__('Previous', 'amnesty')}</span>
                </a>
              </div>
            </div>
          </div>
        `;
      },
      next(data, args) {
        const isDisabled = false;
        const classes = `wp-block-button is-style-pagination ${isDisabled ? 'is-disabled' : ''}`;
        console.log({ args });

        // no way to determine whether current is last page or not from here
        // plus the markup's bloated.
        // will have to use https://www.algolia.com/doc/api-reference/widgets/pagination/js#customize-the-ui-with-connectpagination
        return args.html`
          <div class="wp-block-group post-paginationLink post-paginationPrevious is-layout-flow wp-block-group-is-layout-flow">
            <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
              <div class="${classes}">
                <a class="wp-block-button__link wp-element-button">
                  <span class="icon"></span>
                  <span>${__('Next', 'amnesty')}</span>
                </a>
              </div>
            </div>
          </div>
        `;
      },
    },
  });
}
