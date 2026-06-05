const { _n, _x, sprintf } = wp.i18n;

/**
 * Make the stats widget
 *
 * @see https://www.algolia.com/doc/api-reference/widgets/stats/js/
 */
export default function StatsWidget() {
  if (!window.instantsearch) {
    return null;
  }

  const container = document.querySelector('.postlist-headerTitle');

  if (!container) {
    return null;
  }

  const { locale, months } = window.AlgoliaSearch;
  const variables = Object.fromEntries(new URLSearchParams(window.location.search));

  let formatter;
  try {
    formatter = new Intl.NumberFormat(locale.replace('_', '-'));
  } catch (e) {
    // no-op for invalid locales
    formatter = (value) => value;
  }


  return window.instantsearch.widgets.stats({
    container,
    templates: {
      text(data) {
        const hits = formatter.format(data.nbHits);
        const parts = [];

        /* translators: Singular/Plural number of posts. */
        let title = sprintf(_n('%s result', '%s results', data.nbHits, 'amnesty'), hits);
        if (data.query) {
          title = sprintf(
            /* translators: 1: number of results for search query, 2: don't translate (dynamic search term) */
            _n("%1$s result for '%2$s'", "%1$s results for '%2$s'", data.nbHits, 'amnesty'),
            hits,
            data.query,
          );
        }

        parts.push(title);

        const month = parseInt(variables.qmonth, 10);
        if (month) {
          parts.push(
            sprintf(
              /* translators: [front] appended to search results title (n results for...); %s: the month searched for */
              _x(
                'in the month of %s',
                'search results title suffix for month published',
                'amnesty',
              ),
              months[month],
            ),
          );
        }

        const year = parseInt(variables.qyear, 10);
        if (year) {
          parts.push(
            sprintf(
              /* translators: [front] appended to search results title (n results for...); %s: the year searched for */
              _x('in the year %s', 'search results title suffix for year published', 'amnesty'),
              year,
            ),
          );
        }

        return parts.join(' ');
      },
    },
  });
}
