<?php
/**
 * WP Search With Algolia instantsearch template file.
 *
 * @author  WebDevStudios <contact@webdevstudios.com>
 * @since   1.0.0
 *
 * @version 2.10.2
 * @package WebDevStudios\WPSWA
 */

get_header();

?>

<!-- wp:group {"id":"ais-wrapper","className":"horizontal-search"} -->
<div id="ais-wrapper" class="wp-block-group horizontal-search">
	<!-- wp:group {"id":"ais-main"} -->
	<div id="ais-main" class="wp-block-group">
		<!-- wp:group {"className":"section section--small section--dark postlist-categoriesContainer"} -->
		<div class="wp-block-group section section--small section--dark postlist-categoriesContainer">
			<!-- wp:group {"className":"algolia-search-box-wrapper container initial-filters"} -->
			<div class="wp-block-group algolia-search-box-wrapper container initial-filters">
				<!-- wp:group {"className":"default-search-filters taxonomyArchive-filters"} -->
				<div class="default-search-filters taxonomyArchive-filters">
					<!-- wp:group {"className":"search-input"} -->
					<div id="algolia-search-box" class="wp-block-group search-input">
						<!-- wp:paragraph {"id":"algolia-search-box"} -->
						<p id="algolia-search-box" class="wp-block-paragraph"></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"id":"algolia-stats"} -->
					<div id="algolia-stats" class="wp-block-group"></div>
					<!-- /wp:group -->
					<!-- wp:group {"id":"algolia-powered-by"} -->
					<div id="algolia-powered-by" class="wp-block-group"></div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"id":"algolia-hits"} -->
		<div id="algolia-hits" class="wp-block-group"></div>
		<!-- /wp:group -->
		<!-- wp:group {"id":"algolia-pagination"} -->
		<div id="algolia-pagination" class="wp-block-group"></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"id":"ais-facets","tagName":"aside"} -->
	<aside id="ais-facets" class="wp-block-group">
		<!-- wp:group -->
		<div class="wp-block-group">
			<!-- wp:title {"level":3,"className":"widgettitle"} -->
			<h3 class="wp-block-title widgettitle"><?php esc_html_e( 'Post Types', 'wp-search-with-algolia' ); ?></h3>
			<!-- /wp:title -->
			<!-- wp:group {"id":"facet-post-types","className":"ais-facets","tagName":"section"} -->
			<section id="facet-post-types" class="wp-block-group ais-facets"></section>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group -->
		<div class="wp-block-group">
			<!-- wp:title {"level":3,"className":"widgettitle"} -->
			<h3 class="wp-block-title widgettitle"><?php esc_html_e( 'Categories', 'wp-search-with-algolia' ); ?></h3>
			<!-- /wp:title -->
			<!-- wp:group {"id":"facet-categories","className":"ais-facets","tagName":"section"} -->
			<section id="facet-categories" class="wp-block-group ais-facets"></section>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group -->
		<div class="wp-block-group">
			<!-- wp:title {"level":3,"className":"widgettitle"} -->
			<h3 class="wp-block-title widgettitle"><?php esc_html_e( 'Tags', 'wp-search-with-algolia' ); ?></h3>
			<!-- /wp:title -->
			<!-- wp:group {"id":"facet-tags","className":"ais-facets","tagName":"section"} -->
			<section id="facet-tags" class="wp-block-group ais-facets"></section>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</aside>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<script type="text/javascript">
	window.addEventListener('load', function() {
		// Set a custom user token if you enable insights and don't want the anonymous token.
		// window.aa('setUserToken', 'some-user-id');
		if ( document.getElementById("algolia-search-box") ) {
			if ( algolia.indices.searchable_posts === undefined && document.getElementsByClassName("admin-bar").length > 0 ) {
				alert('<?php esc_html_e( 'It looks like you have not indexed the searchable posts index. Please head to the Indexing page of the Algolia Search plugin and index it.', 'wp-search-with-algolia' ); ?>');
			}

			/* Instantiate instantsearch.js */
			const search = instantsearch({
				indexName: algolia.indices.searchable_posts.name,
				searchClient: algoliasearch( algolia.application_id, algolia.search_api_key ),
				routing: {
					router: instantsearch.routers.history({ writeDelay: 1000 }),
					stateMapping: {
						stateToRoute( indexUiState ) {
							return {
								s: indexUiState[ algolia.indices.searchable_posts.name ].query,
								page: indexUiState[ algolia.indices.searchable_posts.name ].page
							}
						},
						routeToState( routeState ) {
							const indexUiState = {};
							indexUiState[ algolia.indices.searchable_posts.name ] = {
								query: routeState.s,
								page: routeState.page
							};
							return indexUiState;
						}
					}
				},
				// https://www.algolia.com/doc/guides/building-search-ui/events/js/
				insights: algolia.insights_enabled,
				/*
				insights: {
					insightsInitParams: {
						useCookie: true
					}
				},
					*/
			});

			search.addWidgets([

				// Search box widget
				// https://www.algolia.com/doc/api-reference/widgets/search-box/js/
				instantsearch.widgets.searchBox({
					container: '#algolia-search-box',
					placeholder: 'Search for...',
					showReset: false,
					showSubmit: false,
					showLoadingIndicator: false,
				}),

				// Stats widget
				// https://www.algolia.com/doc/api-reference/widgets/stats/js/
				instantsearch.widgets.stats({
					container: '#algolia-stats'
				}),

				// Configure widget
				// https://www.algolia.com/doc/api-reference/widgets/configure/js/
				instantsearch.widgets.configure({
					hitsPerPage: algolia.search_hits_per_page,
				}),

				// Hits widget
				// https://www.algolia.com/doc/api-reference/widgets/hits/js/
				instantsearch.widgets.hits({
					container: '#algolia-hits',
					templates: {
						empty(results, {html} ) {
							return html `No results were found for "<strong>${results.query}</strong>".`;
						},
						item(hit, { html, components }) {
							let content_snippet = '';
							if (hit._snippetResult['content']) {
								content_snippet = html`<span class="suggestion-post-content ais-hits--content-snippet">${components.Snippet({hit, attribute: 'content'})}</span>`;
							}

							return html`
								<article itemtype="https://schema.org/Article">
									<div class="ais-hits--content">
										<h2 itemprop="name headline"><a href="${hit.permalink}" title="${hit.post_title}" class="ais-hits--title-link" itemprop="url">${components.Highlight({hit, attribute: 'post_title'})}</a></h2>
										<div class="excerpt">
											<p>${content_snippet}</p>
										</div>
									</div>
									<div class="ais-clearfix"></div>
								</article>`;
						}
					},
					transformData: {
						item: function (hit) {

							function replace_highlights_recursive (item) {
								if (item instanceof Object && item.hasOwnProperty('value')) {
									item.value = _.escape(item.value);
									item.value = item.value.replace(/__ais-highlight__/g, '<em>').replace(/__\/ais-highlight__/g, '</em>');
								} else {
									for (let key in item) {
										item[key] = replace_highlights_recursive(item[key]);
									}
								}
								return item;
							}

							hit._highlightResult = replace_highlights_recursive(hit._highlightResult);
							hit._snippetResult = replace_highlights_recursive(hit._snippetResult);

							return hit;
						}
					}
				}),

				// Pagination widget
				// https://www.algolia.com/doc/api-reference/widgets/pagination/js/
				instantsearch.widgets.pagination({
					container: '#algolia-pagination'
				}),

				// Post types refinement widget
				// https://www.algolia.com/doc/api-reference/widgets/menu/js/
				instantsearch.widgets.menu({
					container: '#facet-post-types',
					attribute: 'post_type_label',
					sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
					limit: 10,
				}),

				// Categories refinement widget
				// https://www.algolia.com/doc/api-reference/widgets/hierarchical-menu/js/
				instantsearch.widgets.hierarchicalMenu({
					container: '#facet-categories',
					separator: ' > ',
					sortBy: ['count'],
					attributes: ['taxonomies_hierarchical.category.lvl0', 'taxonomies_hierarchical.category.lvl1', 'taxonomies_hierarchical.category.lvl2'],
				}),

				// Tags refinement widget
				// https://www.algolia.com/doc/api-reference/widgets/refinement-list/js/
				instantsearch.widgets.refinementList({
					container: '#facet-tags',
					attribute: 'taxonomies.post_tag',
					operator: 'and',
					limit: 15,
					sortBy: ['isRefined:desc', 'count:desc', 'name:asc'],
				}),
			]);

			if ( algolia.powered_by_enabled ) {
				// Search powered-by widget
				// https://www.algolia.com/doc/api-reference/widgets/powered-by/js/
				search.addWidgets([
					/* Search powered-by widget */
					instantsearch.widgets.poweredBy({
						container: '#algolia-powered-by'
					}),
				])
			}

			/* Start */
			search.start();

			// This needs work
			document.querySelector("#algolia-search-box input[type='search']").select()
		}
	});
</script>

<?php

get_footer();
