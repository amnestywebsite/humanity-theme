<?php

/**
 * Title: Archive loop results
 * Description: Template for the loop on archive pages
 * Slug: amnesty/archive-loop-results
 * Inserter: no
 */

global $wp_query;

$current_sort    = amnesty_get_query_var( 'sort' );
$available_sorts = amnesty_valid_sort_parameters();

$current_sort_option = $available_sorts[ $current_sort ] ?? null;

// move current sort to the top of the list
if ( $current_sort_option ) {
	unset( $available_sorts[ $current_sort ] );
	$available_sorts = [ $current_sort => $current_sort_option ] + $available_sorts;
}

?>
<!-- wp:group {"tagName":"header","className":"postlist-header"} -->
<header class="wp-block-group postlist-header">
	<!-- wp:amnesty-core/query-count /-->
	<!-- wp:amnesty-core/custom-select {"label":"<?php esc_html_e( 'Sort by', 'amnesty' ); ?>","showLabel":true,"name":"sort","isForm":true,"multiple":false,"options":<?php echo wp_kses_data( wp_json_encode( $available_sorts ) ); ?>} /-->
</header>
<!-- /wp:group -->
