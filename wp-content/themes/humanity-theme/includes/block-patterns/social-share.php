<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_block_patterns' ) ) {
	/**
	 * Register Amnesty block patterns
	 *
	 * @package Amnesty\Blocks\Patterns
	 *
	 * @return void
	 */
	function amnesty_register_block_patterns() {
		// Block patterns were not introduced until WP v5.5.0
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}

		register_block_pattern(
			'amnesty/social-share',
			[
				/* translators: [admin] */
				'title'       => __( 'Social Share', 'amnesty' ),
				/* translators: [admin] */
				'description' => __( 'Three column layout with headings, tweet action, social icons, video embed and buttons', 'amnesty' ),
				'categories'  => [ 'amnesty-core' ],
				'content'     => file_get_contents( __DIR__ . '/views/social-share.html' ),
			]
		);

		register_block_pattern(
			'amnesty/66-33-text-with-image',
			[
				/* translators: [admin] */
				'title'       => __( '66/33 Text with Image', 'amnesty' ),
				/* translators: [admin] */
				'description' => __( 'Two column layout with text and image', 'amnesty' ),
				'categories'  => [ 'amnesty-core' ],
				'content'     => file_get_contents( __DIR__ . '/views/66-33-text-with-image.html' ),
			]
		);
	}
}

add_action( 'init', 'amnesty_register_block_patterns' );
