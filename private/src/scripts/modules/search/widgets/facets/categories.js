/**
 * Make the categories facet widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/hierarchical-menu/js/
 */
export default function CategoryFacet() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.getElementById('facet-categories');

  if (!container) {
    return null;
  }

  return window.instantsearch.widgets.hierarchicalMenu({
    container,
    separator: ' > ',
    sortBy: ['count'],
    attributes: [
      'taxonomies_hierarchical.category.lvl0',
      'taxonomies_hierarchical.category.lvl1',
      'taxonomies_hierarchical.category.lvl2',
    ],
  });
}
