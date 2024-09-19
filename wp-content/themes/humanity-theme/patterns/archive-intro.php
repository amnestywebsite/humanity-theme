<?php

/**
 * Title: Taxonomy intro
 * Description: Outputs the title and description for taxonomy terms
 * Slug: amnesty/taxonomy-intro
 * Inserter: no
 */

// this is null on term archives for some reason, meaning that this pattern may not function correctly in some circumstances
$current_term = get_queried_object();

if ( ! is_a( $current_term, WP_Term::class ) ) {
	return;
}

?>

<!-- wp:group {"tagName":"div","className":"categoryTerm-title"} -->
<div class="wp-block-group categoryTerm-title">
	<!-- wp:heading {"level":1} -->
	<h1><?php echo esc_html( $current_term->name ); ?></h1>
	<!-- /wp:heading -->
	<?php echo wp_kses_post( apply_filters( 'the_content', $current_term->description ) ); ?>
</div>
<!-- /wp:group -->
