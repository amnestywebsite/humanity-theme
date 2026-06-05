/**
 * Make the tags facet widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/refinement-list/js/
 */
export default function TagFacet() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.getElementById('facet-tags');

  if (!container) {
    return null;
  }

  return window.instantsearch.widgets.refinementList({
    container,
    attribute: 'taxonomies.post_tag',
    operator: 'and',
    limit: 15,
    sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
  });
}
