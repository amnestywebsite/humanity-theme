<?php

/**
 * Title: Featured Image Hidden
 * Description: Site/Block editor placeholder for a featured image when it has been hidden
 * Slug: amnesty/featured-image-hidden
 * Inserter: no
 */

if ( ! is_admin() ) {
	return;
}

if ( ! get_post_meta( get_the_ID(), '_hide_featured_image', true ) ) {
	return;
}

?>

<!-- wp:group {"className":"is-style-square-border","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-square-border"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><?php esc_html_e( 'The featured image for this post has been hidden.', 'amnesty' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
