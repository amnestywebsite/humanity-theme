<?php

if ( is_shop() ) {
	return;
}

if ( is_tax() ) {
	get_template_part( 'partials/taxonomy/hero' );
	return;
}


$object_id = amnesty_get_header_object_id();
$hero_show = false;
$hero_data = wp_parse_args(
	amnesty_get_header_data( $object_id ),
	[
		'imageID' => absint( get_post_meta( $object_id, '_thumbnail_id', true ) ),
	]
);


$object = get_queried_object();
if ( ! is_singular( [ 'post' ] ) && ! is_search() && ! is_404() ) {
	if ( is_archive() && is_object( $object ) ) {
		$hero_data['attrs']['title']   = $object->label;
		$hero_data['attrs']['content'] = $object->labels->archives ?? $object->description;
	}
}

$hero_show = 0 !== $hero_data['attrs']['imageID'];

if ( $hero_show ) {
	// phpcs:ignore
	echo \Amnesty\Blocks\amnesty_render_header_block( $hero_data['attrs'], $hero_data['content'] );
	amnesty_remove_header_from_content();
}
