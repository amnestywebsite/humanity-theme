import CategoryFacet from './search/widgets/facets/categories';
import TagFacet from './search/widgets/facets/tags';
import HitsWidget from './search/widgets/hits';
import PaginationWidget from './search/widgets/pagination';
import SearchBoxWidget from './search/widgets/searchbox';
import StatsWidget from './search/widgets/stats';

const { algolia, algoliasearch, instantsearch } = window;

function makeSearchInstance() {
  return instantsearch({
    indexName: algolia.indices.searchable_posts.name,
    searchClient: algoliasearch(algolia.application_id, algolia.search_api_key),
    // https://www.algolia.com/doc/guides/building-search-ui/events/js/
    insights: algolia.insights_enabled,
    routing: {
      router: instantsearch.routers.history({
        writeDelay: 1000,
        createURL({ routeState, location }) {
          const urlParts = location.href.match(/^(.*?)\/search/);
          let url = `${urlParts[1]}/search/`;

          if (routeState.query) {
            url += encodeURIComponent(routeState.query);
            url += '/';
          }

          if (routeState.page > 1) {
            url += `page/${routeState.page}/`;
          }
          console.log({ createURL: `${url}` });
          return `${url}`;
        },
        parseURL({ location }) {
          const pathnameMatches = location.pathname.match(
            /^\/[a-zA-Z0-9-_]+\/search\/(([^/]+)(\/page\/(\d+))?\/)?$/,
          );

          const [, , query, , page] = pathnameMatches;

          const parsed = {
            query: decodeURIComponent(query),
            page,
          };

          console.log({ parseURL: parsed });

          return parsed;
        },
      }),
      stateMapping: {
        stateToRoute(indexUiState) {
          const idx = algolia.indices.searchable_posts.name;

          return {
            s: indexUiState[idx].query,
            page: indexUiState[idx].page,
          };
        },
        routeToState(routeState) {
          const idx = algolia.indices.searchable_posts.name;
          const indexUiState = {};

          indexUiState[idx] = {
            query: routeState.s,
            page: routeState.page,
          };

          return indexUiState;
        },
      },
    },
  });
}

function addWidgets(search) {
  const widgets = [
    // https://www.algolia.com/doc/api-reference/widgets/configure/js/
    instantsearch.widgets.configure({
      hitsPerPage: algolia.search_hits_per_page,
    }),

    SearchBoxWidget(),
    StatsWidget(),
    HitsWidget(),
    PaginationWidget(),

    CategoryFacet(),
    TagFacet(),

    // https://www.algolia.com/doc/api-reference/widgets/powered-by/js/
    instantsearch.widgets.poweredBy({
      container: '#algolia-powered-by',
    }),
  ];

  search.addWidgets(widgets);
}

export default function AlgoliaSearch() {
  const searchBox = document.getElementById('algolia-searchbox');

  if (!searchBox) {
    return;
  }

  const search = makeSearchInstance();
  addWidgets(search);

  search.start();
}
