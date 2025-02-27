<?php

if ( $attributes['parallax'] ) {
	require realpath( __DIR__ . '/render-parallax.php' );
}

// the srcset declaration, for the fixed height style, causes much larger images to load than is necessary
$remove_srcset = function ( array $props ) use ( $attributes ): array {
	if ( 'fixed' !== $attributes['style'] ) {
		return $props;
	}

	unset( $props['srcset'], $props['sizes'] );
	return $props;
};

add_filter( 'wp_get_attachment_image_attributes', $remove_srcset );

if ( ! $attributes['parallax'] ) {
	require realpath( __DIR__ . '/render-block.php' );
}

remove_filter( 'wp_get_attachment_image_attributes', $remove_srcset );

$wrapper_classes = classnames(
	'imageBlock-wrapper',
	'u-cf',
	[
		sprintf( 'align%s', $attributes['align'] ) => 'default' !== $attributes['align'],
	]
);
