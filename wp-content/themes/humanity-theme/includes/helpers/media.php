<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_featured_image' ) ) {
	/**
	 * Return the image url of a posts featured image at the desired size.
	 *
	 * @package Amnesty
	 *
	 * @param int|bool $post_id The desired post's ID.
	 * @param string   $size    The desired image size.
	 *
	 * @return bool|false|string
	 */
	function amnesty_featured_image( $post_id = false, $size = 'full' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$img_url = get_the_post_thumbnail_url( $post_id, $size );

		if ( ! $img_url ) {
			return false;
		}

		return $img_url;
	}
}

if ( ! function_exists( 'get_theme_image_sizes' ) ) {
	/**
	 * Gets array of image sizes
	 * 'example' => array( 376, 282, true ),
	 *
	 * @package Amnesty
	 *
	 * @return array
	 */
	function get_theme_image_sizes() {
		return [
			'post-full'        => [ 720, 920, true ],
			'post-half'        => [ 334, 200, true ],
			'post-half@2x'     => [ 640, 400, true ],
			'post-featured'    => [ 914, 527, true ],
			'post-featured@2x' => [ 1828, 1054, true ],
			'grid-item'        => [ 900, 900, true ],
			'image-block'      => [ 0, 600, [ 'center', 'bottom' ] ],
			'hero-location-sm' => [ 734, 0, false ],
			'hero-lg'          => [ 2560, 710, true ],
			'hero-md'          => [ 1468, 710, true ],
			'hero-sm'          => [ 770, 710, true ],
			'logotype'         => [ 0, 72, false ],
			'logotype@2x'      => [ 0, 144, false ],
			'logomark'         => [ 60, 60, false ],
			'logomark@2x'      => [ 120, 120, false ],
			'lwi-block-sm'     => [ 200, 200, false ],
			'lwi-block-sm@2x'  => [ 400, 400, false ],
			'lwi-block-md'     => [ 260, 260, false ],
			'lwi-block-md@2x'  => [ 520, 520, false ],
			'lwi-block-lg'     => [ 325, 325, false ],
			'lwi-block-lg@2x'  => [ 650, 650, false ],
			'wc-thumb'         => [ 335, 335, true ],
			'action-wide'      => [ 480, 230, true ],
			'action-standard'  => [ 350, 230, true ],
		];
	}
}

if ( ! function_exists( 'amnesty_theme_image_sizes' ) ) {
	/**
	 * Loop through and add all of the image sizes declared in `get_theme_image_sizes`.
	 *
	 * @package Amnesty
	 *
	 * @return void
	 */
	function amnesty_theme_image_sizes() {
		foreach ( get_theme_image_sizes() as $label => $dims ) {
			add_image_size( $label, $dims[0], $dims[1], $dims[2] );
		}
	}
}

if ( ! function_exists( 'amnesty_custom_image_sizes' ) ) {
	/**
	 * Declare Amnesty image sizes for the media editor
	 *
	 * @package Amnesty
	 *
	 * @param array $sizes the existing defined sizes
	 *
	 * @return array
	 */
	function amnesty_custom_image_sizes( $sizes ) {
		return array_merge(
			$sizes,
			[
				'post-full'        => 'Post Full',
				'post-half'        => 'Post Half',
				'post-half@2x'     => 'Post Half Retina',
				'post-featured'    => 'Post Featured',
				'post-featured@2x' => 'Post Featured Retina',
				'grid-item'        => 'Grid Item',
				'image-block'      => 'Image Block',
				'hero-lg'          => 'Hero Large',
				'hero-md'          => 'Hero Medium',
				'hero-sm'          => 'Hero Small',
				'logotype'         => 'Logotype',
				'logotype@2x'      => 'Logotype Retina',
				'logomark'         => 'Logomark',
				'logomark@2x'      => 'logomark Retina',
				'lwi-block-sm'     => 'LWI Block Small',
				'lwi-block-sm@2x'  => 'LWI Block Small Retina',
				'lwi-block-md'     => 'LWI Block Medium',
				'lwi-block-md@2x'  => 'LWI Block Medium Retina',
				'lwi-block-lg'     => 'LWI Block Large',
				'lwi-block-lg@2x'  => 'LWI Block Large Retina',
				'wc-thumb'         => 'WC thumb',
				'action-wide'      => 'Action Wide',
				'action-wide@2x'   => 'Action Wide Retina',
			]
		);
	}
}
