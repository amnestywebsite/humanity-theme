<?php

/**
 * Global partial, sidebar
 *
 * @package Amnesty\Partials
 */

if ( amnesty_get_meta_field( '_disable_sidebar' ) === '1' ) {
	return;
}

$sidebar_id = amnesty_get_meta_field( '_sidebar_id' );

if ( ! $sidebar_id || 0 === $sidebar_id ) {
	$sidebar_key = is_page() ? '_default_sidebar_page' : '_default_sidebar';
	$sidebar_id  = amnesty_get_option( $sidebar_key );
}

if ( $sidebar_id && is_array( $sidebar_id ) ) {
	$sidebar_id = array_shift( $sidebar_id );
}

if ( ! $sidebar_id ) {
	return;
}

$query = new WP_Query(
	[
		'post__in'      => [ $sidebar_id ],
		'post_type'     => 'sidebar',
		'no_found_rows' => true,
	] 
);

while ( $query->have_posts() ) {
	$query->the_post();
	the_content();
}

wp_reset_postdata();
