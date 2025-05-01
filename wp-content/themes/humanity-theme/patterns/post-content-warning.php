<?php

/**
 * Title: Content Warning
 * Description: Output the content warning for a post
 * Slug: amnesty/post-content-warning
 * Inserter: no
 */

$warning = get_post_meta( get_the_ID(), 'content_warning', true );

if ( ! $warning ) {
	return;
}

?>

<span><?php echo wp_kses_post( wpautop( $warning ) ); ?></span>
