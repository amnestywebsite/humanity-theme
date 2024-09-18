<?php

/**
 * Title: Archive filters
 * Description: Outputs filters for post type archive pages
 * Slug: amnesty/archive-filters
 * Inserter: no
 */

$current_term     = get_queried_object();
$term_name        = $current_term->name ?? '';
$term_description = $current_term->description ?? '';

if ( ! $term_name && ! $term_description ) {
	return;
}

?>

<!-- wp:group {"tagName":"div","className":"categoryTerm-title"} -->
<div class="wp-block-group categoryTerm-title">
	<!-- wp:heading {"level":1} -->
	<h1 class="wp-block-heading"><?php echo esc_html( $term_name ); ?></h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php echo wp_kses_post( $term_description ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
