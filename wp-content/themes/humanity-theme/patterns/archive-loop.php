<?php

/**
 * Title: Archive loop
 * Description: Template for the loop on archive pages
 * Slug: amnesty/archive-loop
 * Inserter: no
 */

add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_archive' );

?>

<!-- wp:query {"inherit":true,"className":"news-section section section--small section--tinted has-gutter"} -->
<div class="wp-block-query news-section section section--small section--tinted has-gutter">
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
<!-- wp:query-pagination {"align":"center","className":"section section--small","paginationArrow":"none","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<!-- wp:query-pagination-previous {"label":"Previous"} /-->
	<!-- wp:query-pagination-numbers /-->
	<!-- wp:query-pagination-next {"label":"Next"} /-->
<!-- /wp:query-pagination -->
<!-- /wp:query -->
