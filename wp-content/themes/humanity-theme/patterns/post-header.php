<?php

/**
 * Title: Post Header
 * Description: Outputs the post's header (deprecated - replaced by the hero), if any
 * Slug: amnesty/post-header
 * Inserter: no
 */

use function Amnesty\Blocks\amnesty_render_header_block;

if ( ! amnesty_post_has_header() ) {
	return;
}

$hero_data = amnesty_get_header_data();

if ( ! array_filter( $hero_data ) ) {
	return;
}

add_filter( 'wp_kses_allowed_html', 'amnesty_wp_kses_post_allow_style_tag', 10, 2 );
echo wp_kses_post( amnesty_render_header_block( $hero_data['attrs'], $hero_data['content'] ) );
remove_filter( 'wp_kses_allowed_html', 'amnesty_wp_kses_post_allow_style_tag', 10, 2 );

if ( ! is_admin() ) {
	add_filter( 'the_content', 'amnesty_remove_header_from_content', 0 );
}
