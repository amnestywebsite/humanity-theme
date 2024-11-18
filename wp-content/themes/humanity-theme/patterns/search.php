<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';
$search_object = amnesty_get_searchpage_query_object( false );
$order_vars    = $search_object->get_order_vars();

$args = [
	'inherit' => false,
	'query'   => [
		'perPage'  => null,
		'pages'    => 0,
		'offset'   => 0,
		'postType' => apply_filters( 'amnesty_list_query_post_types', [ 'page', 'post' ] ),
		'order'    => $order_vars['order'],
		'orderby'  => $order_vars['orderby'],
		'author'   => '',
		'search'   => '', // if there's a term, we'll be on a different template
		'exclude'  => [], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		'sticky'   => false,
		'taxQuery' => $search_object->build_tax_args(),
		'parents'  => [],
	],
];

// custom queries sometimes break retrieval in the site editor
if ( is_admin() ) {
	$args['query'] = [];
}

add_filter( 'query_loop_block_query_vars', fn () => $args['query'] );

if ( amnesty_get_query_var( 'qyear' ) ) {
	add_filter( 'query_loop_block_query_vars', fn ( array $vars ): array => $vars + [ 'year' => absint( amnesty_get_query_var( 'qyear' ) ) ] );
}

if ( amnesty_get_query_var( 'qmonth' ) ) {
	add_filter( 'query_loop_block_query_vars', fn ( array $vars ): array => $vars + [ 'monthnum' => absint( amnesty_get_query_var( 'qmonth' ) ) ] );
}

// add filter to limit the post terms results for search
add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_search' );

$current_sort     = amnesty_get_query_var( 'sort' );
$available_sorts  = amnesty_valid_sort_parameters();
$sort_select_args = [
	'label'     => __( 'Sort by', 'amnesty' ),
	'showLabel' => true,
	'name'      => 'sort',
	'isForm'    => true,
	'multiple'  => false,
	'options'   => $available_sorts,
	'active'    => $current_sort,
];

?>

<!-- wp:query <?php echo wp_kses_data( wp_json_encode( $args ) ); ?> -->
<div class="wp-block-query">
	<!-- wp:group {"tagName":"div","className":"section section--tinted search-results"} -->
	<div class="wp-block-group section section--tinted search-results">
		<!-- wp:group {"tagName":"header","className":"postlist-header"} -->
		<header class="wp-block-group postlist-header">
			<!-- wp:amnesty-core/search-title /-->
			<!-- wp:amnesty-core/custom-select <?php echo wp_kses_data( wp_json_encode( $sort_select_args ) ); ?> /-->
		</header>
		<!-- /wp:group -->

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
		<!-- wp:query-pagination-previous {"label":"Previous","paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>} /-->
		<!-- wp:query-pagination-numbers {"midSize":1,"paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>,"pretty":true,"className":"page-numbers"} /-->
		<!-- wp:query-pagination-next {"label":"Next","paged":<?php echo absint( get_query_var( 'paged' ) ?: 1 ); ?>} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
