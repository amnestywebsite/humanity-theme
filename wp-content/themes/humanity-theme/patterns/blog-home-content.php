<?php

/**
 * Title: Blog home content
 * Description: Outputs post content on the blog home if it is set to a static page
 * Slug: amnesty/blog-home-content
 * Inserter: no
 */

if ( ! is_home() || ! get_option( 'page_for_posts' ) ) {
	return;
}

echo wp_kses( get_post_field( 'post_content', get_option( 'page_for_posts' ) ), 'slider' );
