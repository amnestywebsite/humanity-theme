<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';
$search_object = amnesty_get_searchpage_query_object( false );

$block_args = [
	'queryId' => 0,
	'query'   => $search_object->get_block_vars(),
];

// custom queries sometimes break retrieval in the site editor
if ( is_admin() ) {
	$block_args['query'] = [];
}

add_filter( 'query_loop_block_query_vars', fn () => $search_object->get_query_vars() );

if ( amnesty_get_query_var( 'qyear' ) ) {
	add_filter( 'query_loop_block_query_vars', fn ( array $vars ): array => $vars + [ 'year' => absint( amnesty_get_query_var( 'qyear' ) ) ] );
}

if ( amnesty_get_query_var( 'qmonth' ) ) {
	add_filter( 'query_loop_block_query_vars', fn ( array $vars ): array => $vars + [ 'monthnum' => absint( amnesty_get_query_var( 'qmonth' ) ) ] );
}

// add filter to limit the post terms results for search
add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_search' );

?>

<!-- wp:query <?php echo wp_kses_data( wp_json_encode( $block_args ) ); ?> -->
<div class="wp-block-query">
	<!-- wp:group {"tagName":"div","className":"section section--tinted search-results"} -->
	<div class="wp-block-group section section--tinted search-results">
		<!-- wp:amnesty-core/search-header /-->
		<!-- wp:post-template {"layout":{"type":"constrained","justifyContent":"left"}} -->

		<!-- wp:group {"tagName":"article","className":"post post--result"} -->
		<article class="wp-block-group post post--result">
			<!-- wp:group {"tagName":"div","className":"post-terms","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group post-terms">
				<!-- wp:post-terms {"term":"category","className":"post-category"} /-->
				<!-- wp:post-terms {"term":"<?php echo esc_attr( $location_slug ); ?>","className":"post-location"} /-->
				<!-- wp:post-terms {"term":"topic","className":"post-topic"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:post-title {"isLink":true,"className":"post-title"} /-->
			<!-- wp:post-excerpt {"className":"post-excerpt"} /-->
			<!-- wp:post-date {"className":"post-byline"} /-->
		</article>
		<!-- /wp:group -->

		<!-- /wp:post-template -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query-pagination {"align":"center","className":"section section--small","paginationArrow":"none","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
		<!-- wp:query-pagination-previous {"label":"<?php echo esc_html( __( 'Previous', 'amnesty' ) ); ?>","paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>} /-->
		<!-- wp:query-pagination-numbers {"midSize":1,"paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>,"pretty":true,"className":"page-numbers"} /-->
		<!-- wp:query-pagination-next {"label":"<?php echo esc_html( __( 'Next', 'amnesty' ) ); ?>","paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
