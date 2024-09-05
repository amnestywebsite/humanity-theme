<?php

/**
 * Archives partial, header
 *
 * @package Amnesty\Partials
 */

global $wp_query;

$found_posts     = absint( $wp_query->found_posts );
$found_posts_fmt = number_format_i18n( $found_posts );
$current_sort    = get_query_var( 'sort' ) ?: ( $GLOBALS['wp']->query_vars['sort'] ?? '' );
$available_sorts = amnesty_valid_sort_parameters();

/* translators: Singular/Plural number of posts. */
$results = sprintf( _n( '%s result', '%s results', $found_posts, 'amnesty' ), $found_posts_fmt );

if ( is_search() && get_search_query() ) {
	/* translators: 1: number of results for search query, 2: don't translate (dynamic search term) */
	$results = sprintf( _n( "%1\$s result for '%2\$s'", "%1\$s results for '%2\$s'", $found_posts, 'amnesty' ), $found_posts_fmt, get_search_query() );
}

$results = apply_filters( 'amnesty_search_results_title', $results, $found_posts, get_search_query() );

?>

<header class="postlist-header" aria-label="<?php /* translators: [front] https://isaidotorgstg.wpengine.com/en/search/hey/?qtopic=2063 Label for post results count & sort options */ esc_attr_e( 'Results information', 'amnesty' ); ?>">
	<h2 class="postlist-headerTitle">
		<?php echo esc_html( $results ); ?>
	</h2>
	<?php

	$current_sort_option = $available_sorts[ $current_sort ] ?? null;

	// move current sort to the top of the list
	if ( $current_sort_option ) {
		unset( $available_sorts[ $current_sort ] );
		$available_sorts = [ $current_sort => $current_sort_option ] + $available_sorts;
	}

	amnesty_render_custom_select(
		[
			'label'      => __( 'Sort by', 'amnesty' ),
			'show_label' => true,
			'name'       => 'sort',
			'is_form'    => true,
			'multiple'   => false,
			'options'    => $available_sorts,
		]
	);


	?>
</header>
