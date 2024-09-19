<?php

/**
 * Title: Post Byline (Custom)
 * Description: Output the public byline for a post
 * Slug: amnesty/post-byline-custom
 * Inserter: no
 */

$enabled = get_post_meta( get_the_ID(), '_display_author_info', true );

if ( ! $enabled ) {
	return;
}

$use_author = get_post_meta( get_the_ID(), 'byline_is_author', true );

if ( $use_author ) {
	return;
}

$byline_entity  = get_post_meta( get_the_ID(), 'byline_entity', true );
$byline_context = get_post_meta( get_the_ID(), 'byline_context', true );

if ( ! $byline_entity && ! $byline_context ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"bylineContainer"} -->
<div class="wp-block-group bylineContainer">
	<!-- wp:group {"tagName":"div","className":"authorInfoContainer"} -->
	<div class="wp-block-group authorInfoContainer">
	<?php

	if ( $byline_entity ) {
		/* translators: [front] */
		echo wp_kses_post( sprintf( '<span>%s %s</span>', _x( 'By', 'author attribution (as in "written by")', 'amnesty' ), $byline_entity ) );
	}

	if ( $byline_context ) {
		echo wp_kses_post( sprintf( ',&nbsp;<span>%s</span>', $byline_context ) );
	}

	?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
