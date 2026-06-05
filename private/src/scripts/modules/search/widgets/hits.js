import { recursivelyReplaceHighlights } from '../utils';

function Term(taxonomy, termName, html) {
  const termObject = window.AlgoliaSearch[taxonomy][termName];

  if (!termObject) {
    return html``;
  }

  if (!termObject.permalink) {
    return html`
      <p class="wp-block-paragraph">
        <span>${termName}</span>
      </p>
    `;
  }

  return html`
    <p class="wp-block-paragraph">
      <a href="${termObject.permalink}" rel="tag">${termName}</a>
    </p>
  `;
}

function Taxonomy(taxonomy, levels, html) {
  if (!window.AlgoliaSearch[taxonomy]) {
    return html``;
  }

  const markup = Term(taxonomy, levels.lvl0[0], html);
  const classes = `wp-block-group taxonomy-${taxonomy} post-${taxonomy} wp-block-post-terms is-layout-flow wp-block-group-is-layout-flow`;

  return html`<div class="${classes}">${markup}</div>`;
}

function Taxonomies(hit, html) {
  if (!hit.taxonomies_hierarchical) {
    return html``;
  }

  const markup = Object.keys(hit.taxonomies_hierarchical).map((taxSlug) =>
    Taxonomy(taxSlug, hit.taxonomies_hierarchical[taxSlug], html),
  );

  return html`${markup}`;
}

/**
 * Render an item
 */
function Item(hit, { html, components }) {
  let contentSnippet = '';
  // eslint-disable-next-line no-underscore-dangle
  if (hit._snippetResult.content) {
    contentSnippet = html`<span class="suggestion-post-content ais-hits--content-snippet">
      ${components.Snippet({ hit, attribute: 'content' })}
    </span>`;
  }

  const taxonomiesMarkup = Taxonomies(hit, html);

  return html`<article
    class="wp-block-group post post--result"
    itemtype="https://schema.org/Article"
  >
    <div class="wp-block-group post-terms is-nowrap is-layout-flex wp-block-group-is-layout-flex">
      ${taxonomiesMarkup}
    </div>
    <h2 class="wp-block-heading post-title wp-block-post-title" itemprop="name headline">
      <a href="${hit.permalink}" title="${hit.post_title}" itemprop="url">
        ${components.Highlight({ hit, attribute: 'post_title' })}
      </a>
    </h2>
    <div class="wp-block-group post-excerpt wp-block-post-excerpt">
      <p class="wp-block-paragraph wp-block-post-excerpt__excerpt">${contentSnippet}</p>
    </div>
    <div class="wp-block-group post-byline wp-block-post-date">
      <p class="wp-block-paragraph">
        <time datetime="${hit.post_date_gmt}">${hit.post_date_gmt}</time>
      </p>
    </div>
    <div class="ais-clearfix"></div>
  </article>`;
}

/**
 * Make the hits widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/hits/js/
 *
 * @return void
 */
export default function HitsWidget() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.querySelector('.search-results');

  if (!container) {
    return null;
  }

  return window.instantsearch.widgets.hits({
    container,
    cssClasses: {
      list: ['wp-block-list', 'wp-block-post-template', 'is-layout-constrained'],
      item: ['wp-block-list-item', 'wp-block-post'],
    },
    templates: {
      empty(results, { html }) {
        return html`No results were found for "<strong>${results.query}</strong>".`;
      },
      item: Item,
    },
    transformData: {
      item(hit) {
        const transformed = { ...hit };
        /* eslint-disable no-underscore-dangle */
        transformed._highlightResult = recursivelyReplaceHighlights(hit._highlightResult);
        transformed._snippetResult = recursivelyReplaceHighlights(hit._snippetResult);
        /* eslint-enable no-underscore-dangle */

        return transformed;
      },
    },
  });
}
