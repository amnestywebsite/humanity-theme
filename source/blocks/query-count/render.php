<?php

global $wp_query;

$found_posts = absint( $wp_query->found_posts );
$formatted   = number_format_i18n( $found_posts );

/* translators: Singular/Plural number of posts. */
$results = sprintf( _n( '%s result', '%s results', $found_posts, 'amnesty' ), $formatted );

if ( is_search() && get_search_query() ) {
	/* translators: 1: number of results for search query, 2: don't translate (dynamic search term) */
	$results = sprintf( _n( "%1\$s result for '%2\$s'", "%1\$s results for '%2\$s'", $found_posts, 'amnesty' ), $formatted, get_search_query() );
}

$results = apply_filters( 'amnesty_search_results_title', $results, $found_posts, get_search_query() );

?>

<h2 class="wp-block-amnesty-core-query-count postlist-headerTitle"><?php echo esc_html( $results ); ?></h2>
