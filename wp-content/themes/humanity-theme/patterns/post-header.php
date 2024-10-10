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

echo wp_kses_post( amnesty_render_header_block( $hero_data['attrs'], $hero_data['content'] ) );

if ( ! is_admin() ) {
	add_filter( 'the_content', 'amnesty_remove_header_from_content', 0 );
}
