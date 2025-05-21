<?php

/**
 * Title: Archive loop
 * Description: Template for the loop on archive pages
 * Slug: amnesty/archive-loop
 * Inserter: yes
 */

add_filter( 'term_links-category', fn ( array $links ): array => [ current( $links ) ] );

?>
<!-- wp:query {"inherit":true} -->
<div class="wp-block-query">
	<!-- wp:group {"tagName":"div","className":""} -->
	<div class="wp-block-group news-section section section--small section--tinted has-gutter">
		<!-- wp:pattern {"slug":"amnesty/archive-loop-results"} /-->
		<!-- wp:group {"tagName":"div","className":"postlist"} -->
		<div class="wp-block-group postlist">
			<!-- wp:post-template {"layout":{"type":"grid","columnCount":"4"}} -->
			<!-- wp:group {"tagName":"article","className":"aimc-ignore"} -->
			<article class="wp-block-group aimc-ignore">
				<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"post-half","className":"post-figure"} /-->
				<!-- wp:group {"tagName":"div","className":"post-content"} -->
				<div class="wp-block-group post-content">
					<!-- wp:post-terms {"term":"category","className":"post-category"} /-->
					<!-- wp:group {"tagName":"header","className":"post-header"} -->
					<header class="wp-block-group post-header">
						<!-- wp:post-date {"className":"post-meta"} /-->
						<!-- wp:amnesty-core/post-meta {"className":"post-meta","metaKey":"amnesty_updated","isSingle":true} /-->
						<!-- wp:post-title {"isLink":true,"className":"post-title"} /-->
					</header>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</article>
			<!-- /wp:group -->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query-pagination {"align":"center","className":"section section--small","paginationArrow":"none","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
		<!-- wp:query-pagination-previous {"label":"<?php echo esc_html( __( 'Previous', 'amnesty' ) ); ?>"} /-->
		<!-- wp:query-pagination-numbers {"midSize":1,"className":"page-numbers"} /-->
		<!-- wp:query-pagination-next {"label":"<?php echo esc_html( __( 'Next', 'amnesty' ) ); ?>"} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
