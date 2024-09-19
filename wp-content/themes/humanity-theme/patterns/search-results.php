<?php

/**
 * Title: Search results pattern
 * Description: Search results pattern for the theme
 * Slug: amnesty/search-results
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

// add filter to limit the post terms results for search
add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_search' );

?>
<!-- wp:query {"inherit":true} -->
<div class="wp-block-query">
	<!-- wp:group {"tagName":"div","className":"section section--tinted"} -->
	<div class="wp-block-group section section--tinted search-results">
		<!-- wp:amnesty-core/archive-header /-->
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
	</div>
	<!-- /wp:group -->

	<!-- wp:query-pagination {"align":"center","className":"section section--small","paginationArrow":"none","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
		<!-- wp:query-pagination-previous {"label":"Previous"} /-->
		<!-- wp:query-pagination-numbers {"midSize":1,"className":"page-numbers"} /-->
		<!-- wp:query-pagination-next {"label":"Next"} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
