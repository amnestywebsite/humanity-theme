<?php

/**
 * Title: Archive filters
 * Description: Outputs filters for post type archive pages
 * Slug: amnesty/archive-filters
 * Inserter: no
 */

$current_term = get_queried_object();

if ( ! is_a( $current_term, WP_Term::class ) ) {
	return;
}

?>

<!-- wp:group {"tagName":"div","className":"categoryTerm-title"} -->
<div class="wp-block-group categoryTerm-title">
	<!-- wp:heading {"level":1} -->
	<h1 class="wp-block-heading"><?php echo esc_html( $current_term->name ); ?></h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php echo wp_kses_post( $current_term->description ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
