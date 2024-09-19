<?php

/**
 * Title: Home intro
 * Description: Outputs the content for the home template when set to static page
 * Slug: amnesty/home-intro
 * Inserter: no
 */

if ( ! get_option( 'page_for_posts' ) ) {
	return;
}

$content = get_post_field( 'post_content', get_option( 'page_for_posts' ) );

echo wp_kses_post( apply_filters( 'the_content', $content ) );
