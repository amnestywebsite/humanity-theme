const { __ } = wp.i18n;
/**
 * Make the search box widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/search-box/js/
 */
export default function SearchBoxWidget() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.getElementById('algolia-searchbox');

  if (!container) {
    return null;
  }

  return window.instantsearch.widgets.searchBox({
    container,
    placeholder: __('What are you looking for?', 'amnesty'),
    showReset: false,
    showSubmit: true,
    showLoadingIndicator: true,
    cssClasses: {
      submit: ['btn', 'search-button'],
    },
    templates: {
      submit({ cssClasses }, { html }) {
        return html`
          <span class="search-button-text">${__('Submit', 'amnesty')}</span>
          <i class="icon icon-search"></i>
        `;
      },
    },
  });
}
