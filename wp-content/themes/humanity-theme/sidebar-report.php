<?php

/**
 * Global partial, sidebar, locations taxonomy, report type
 *
 * @package Amnesty\Partials
 */

if ( amnesty_get_meta_field( 'hideSidebar', $args['report'], true ) === '1' ) {
	return;
}

$sidebar_id = (array) amnesty_get_meta_field( 'sidebarId', $args['report'], true );

if ( 0 === absint( $sidebar_id[0] ) ) {
	$sidebar_id = (array) amnesty_get_option( 'default_sidebar', 'amnesty_annual_report_options' );
}

if ( empty( $sidebar_id[0] ) ) {
	return;
}

$query = new WP_Query(
	[
		'p'              => absint( $sidebar_id[0] ),
		'post_type'      => 'sidebar',
		'no_found_rows'  => true,
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	] 
);

while ( $query->have_posts() ) {
	$query->the_post();
	the_content();
}

wp_reset_postdata();
