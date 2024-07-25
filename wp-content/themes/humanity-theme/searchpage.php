<?php

/**
 * Template Name: Search Page
 *
 * @package Amnesty\Templates
 */

if ( ! headers_sent() && amnesty_searchpage_has_filters() ) {
	header( 'X-Robots-Tag: noindex', true );
}

get_header();

$query_object = amnesty_get_searchpage_query_object();

$locations = [];

if ( absint( get_query_var( 'paged' ) ) < 2 ) {
	$locations = amnesty_get_terms_from_query( 'location' );
	// add locations count to total results count for the query
	add_filter_once( 'amnesty_searchpage_count', fn ( int $count ) => $count + count( $locations ) );
}

?>
<main id="main">
	<div class="container search-container has-gutter">
		<?php get_template_part( 'partials/search/horizontal-search' ); ?>

		<section class="section search-results section--tinted" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Search results', 'amnesty' ); ?>">
		<?php

		do_action( 'amnesty_searchpage_before_header' );

		require locate_template( 'partials/search/header.php' );

		do_action( 'amnesty_before_search_results' );

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		foreach ( $query_object->get_results() as $post ) {
			setup_postdata( $post );
			get_template_part( 'partials/post/post', 'search' );
		}

		wp_reset_postdata();

		do_action( 'amnesty_after_search_results' );

		?>
		</section>
	</div>

	<div class="container has-gutter">
		<?php require locate_template( 'partials/search/pagination.php' ); ?>
	</div>

</main>
<?php

// phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query
wp_reset_query();

get_footer();
