<?php

/**
 * Title: Search header pattern
 * Description: Search title, filters, etc.
 * Slug: amnesty/search-header
 * Inserter: no
 */

$search_object = amnesty_get_searchpage_query_object();

// add filter to limit the post terms results for search
add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_search' );

$found_posts     = absint( $search_object->get_wp_query()->found_posts );
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

<!-- wp:group {"tagName":"header","className":"postlist-header"} -->
<header class="wp-block-group postlist-header">
	<!-- wp:heading {"className":"postlist-headerTitle"} -->
	<h2 class="wp-block-heading postlist-headerTitle">
		<?php echo esc_html( $results ); ?>
	</h2>
	<!-- /wp:heading -->
	<?php

	// goes haywire in admin
	if ( ! is_admin() && ! ( defined( 'REST_REQUEST' ) && ! REST_REQUEST ) ) {
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
	}

	?>
</header>
<!-- /wp:group -->
