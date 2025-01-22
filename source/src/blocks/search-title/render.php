<?php

if ( ! isset( $block->context['query'] ) ) {
	return;
}

$query = new WP_Query( $block->context['query'] );

$found_posts     = absint( $query->found_posts );
$found_posts_fmt = number_format_i18n( $found_posts );
$current_sort    = amnesty_get_query_var( 'sort' );
$available_sorts = amnesty_valid_sort_parameters();

/* translators: Singular/Plural number of posts. */
$results = sprintf( _n( '%s result', '%s results', $found_posts, 'amnesty' ), $found_posts_fmt );

if ( is_search() && get_search_query() ) {
	/* translators: 1: number of results for search query, 2: don't translate (dynamic search term) */
	$results = sprintf( _n( "%1\$s result for '%2\$s'", "%1\$s results for '%2\$s'", $found_posts, 'amnesty' ), $found_posts_fmt, get_search_query() );
}

$results = apply_filters( 'amnesty_search_results_title', $results, $found_posts, get_search_query() );

?>

<h2 class="postlist-headerTitle">
	<?php echo esc_html( $results ); ?>
</h2>
