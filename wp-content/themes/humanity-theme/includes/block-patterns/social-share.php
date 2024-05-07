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
			'amnesty/66-33-text-key-facts',
			[
				/* translators: [admin] */
				'title'       => __( '66/33 Text Key Facts', 'amnesty' ),
				/* translators: [admin] */
				'description' => __( 'Two column layout with headings, paragraphs, and key facts', 'amnesty' ),
				'categories'  => [ 'amnesty-core' ],
				'content'     => file_get_contents( __DIR__ . '/views/66-33-text-key-facts.html' ),
			]
		);

		register_block_pattern(
			'amnesty/links-with-icons-single',
			[
				/* translators: [admin] */
				'title'       => __( 'Links with Icons Single', 'amnesty' ),
				/* translators: [admin] */
				'description' => __( 'Single column layout with headings, image and paragraph', 'amnesty' ),
				'categories'  => [ 'amnesty-core' ],
				'content'     => file_get_contents( __DIR__ . '/views/links-with-icons-single.html' ),
			]
		);
	}
}

add_action( 'init', 'amnesty_register_block_patterns' );
