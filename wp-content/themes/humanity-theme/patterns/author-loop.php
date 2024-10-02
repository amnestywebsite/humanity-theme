<?php

/**
 * Title: Author loop
 * Description: Template for the loop on author pages
 * Slug: amnesty/author-loop
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

?>

<!-- wp:group {"tagName":"div","className":"more-from-author"} -->
<div class="wp-block-group more-from-author">
	<!-- wp:heading {"level":3} -->
	<h3 class="wp-block-heading"><?php esc_html_e( 'More from this author', 'amnesty' ); ?></h3>
	<!-- /wp:heading -->
	<!-- wp:query {"inherit":true} -->
	<div class="wp-block-query">
		<!-- wp:group {"inherit":true,"className":"author-post-feed section section--tinted"} -->
		<div class="wp-block-group author-post-feed section section--tinted">
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
			<!-- wp:query-pagination-previous {"label":"Previous"} /-->
			<!-- wp:query-pagination-numbers {"midSize":1,"className":"page-numbers"} /-->
			<!-- wp:query-pagination-next {"label":"Next"} /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
