<?php

/**
 * Title: Search results pattern
 * Description: Search results pattern for the theme
 * Slug: amnesty/search-results
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

?>
<!-- wp:query {"inherit":true,"className":"section section--tinted"} -->
<div class="wp-block-query section section--tinted">
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
<!-- /wp:query -->
<!-- wp:query-pagination {"align":"center","className":"section section--small","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<!-- wp:query-pagination-previous /-->
	<!-- wp:query-pagination-numbers /-->
	<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
