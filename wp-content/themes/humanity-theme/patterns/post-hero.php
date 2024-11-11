<?php

/**
 * Title: Post Hero
 * Description: Outputs the post's hero, if any
 * Slug: amnesty/post-hero
 * Inserter: no
 */

if ( ! amnesty_post_has_hero() ) {
	// deprecated
	if ( amnesty_post_has_header() ) {
		echo '<!-- wp:pattern {"slug":"amnesty/post-header"} /-->';
	}

	return;
}

$hero_data = amnesty_get_hero_data();

if ( ! array_filter( $hero_data ) ) {
	return;
}

echo wp_kses_post( render_hero_block( $hero_data['attrs'], $hero_data['content'], $hero_data['name'] ) );

if ( ! is_admin() ) {
	wp_enqueue_style( 'amnesty-hero-style' );
	add_filter( 'the_content', 'amnesty_remove_first_hero_from_content', 0 );
}
