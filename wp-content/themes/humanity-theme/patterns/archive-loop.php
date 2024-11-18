<?php

/**
 * Title: Archive loop
 * Description: Template for the loop on archive pages
 * Slug: amnesty/archive-loop
 * Inserter: yes
 */

add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_archive' );

$current_sort    = amnesty_get_query_var( 'sort' );
$available_sorts = amnesty_valid_sort_parameters();

$current_sort_option = $available_sorts[ $current_sort ] ?? null;

// move current sort to the top of the list
if ( $current_sort_option ) {
	unset( $available_sorts[ $current_sort ] );
	$available_sorts = [ $current_sort => $current_sort_option ] + $available_sorts;
}

?>
<!-- wp:query {"inherit":true} -->
<div class="wp-block-query">
	<!-- wp:group {"tagName":"div","className":""} -->
	<div class="wp-block-group news-section section section--small section--tinted has-gutter">
		<!-- wp:group {"tagName":"header","className":"postlist-header"} -->
		<header class="wp-block-group postlist-header">
			<!-- wp:amnesty-core/search-title /-->
			<!-- wp:amnesty-core/custom-select {"label":"<?php esc_html_e( 'Sort by', 'amnesty' ); ?>","showLabel":true,"name":"sort","isForm":true,"multiple":false,"options":<?php echo wp_kses_data( wp_json_encode( $available_sorts ) ); ?>} /-->
		</header>
		<!-- /wp:group -->

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
