<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

// Add the search page query vars to the query loop block
add_filter(
	'query_loop_block_query_vars',
	function (): array {
		return amnesty_get_searchpage_query_object()->get_query_vars();
	}
);

?>

<!-- wp:query {"queryId":0,"query":{"perPage":12,"postType":"post","inherit":false},"className":"section--tinted"} -->
<div class="wp-block-query section--tinted">
	<!-- wp:post-template {"layout":{"type":"constrained","justifyContent":"left"}} -->

	<!-- wp:group {"tagName":"article","className":"post post--result"} -->
	<article class="wp-block-group post post--result">
		<!-- wp:post-terms {"term":"category","className":"post-category"} /-->
		<!-- wp:post-terms {"term":"<?php echo esc_attr( $location_slug ); ?>","className":"post-location"} /-->
		<!-- wp:post-terms {"term":"topic","className":"post-topic"} /-->
		<!-- wp:post-title {"isLink":true,"className":"post-title"} /-->
		<!-- wp:post-excerpt {"className":"post-excerpt"} /-->
		<!-- wp:post-date {"className":"post-byline"} /-->
	</article>
	<!-- /wp:group -->

	<!-- /wp:post-template -->
	<!-- wp:query-pagination {"align":"center","className":"section section--small post-paginationContainer","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<!-- wp:query-pagination-previous {"className":"post-paginationLink post-paginationPrevious"} /-->
	<!-- wp:query-pagination-numbers {"className":"page-numbers"} /-->
	<!-- wp:query-pagination-next {"className":"post-paginationLink post-paginationNext"} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
