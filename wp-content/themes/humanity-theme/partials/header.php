<?php

if ( function_exists( 'is_shop' ) && is_shop() ) {
	return;
}

if ( is_tax() ) {
	get_template_part( 'partials/taxonomy/hero' );
	return;
}


$object_id = amnesty_get_header_object_id();

if ( ! amnesty_post_has_header( $object_id ) ) {
	return;
}

$hero_show = false;
$hero_data = amnesty_get_header_data( $object_id );

$hero_data['attrs'] = wp_parse_args(
	$hero_data['attrs'],
	[
		'imageID' => absint( get_post_meta( $object_id, '_thumbnail_id', true ) ),
	],
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
