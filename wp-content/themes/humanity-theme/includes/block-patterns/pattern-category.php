<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_core_register_pattern_category' ) ) {
	/**
	 * Add custom category to Gutenberg for easy selection of custom block patterns
	 *
	 * @package Amnesty\Blocks\Patterns
	 *
	 * @return void
	 */
	function amnesty_core_register_pattern_category() {
		// Block patterns were not introduced until WP v5.5.0
		if ( ! function_exists( 'register_block_pattern_category' ) ) {
			return;
		}

		register_block_pattern_category(
			'humanity',
			[
				/* translators: [admin] */
				'label' => __( 'Humanity Theme', 'amnesty' ),
			]
		);
	}
}

add_action( 'init', 'amnesty_core_register_pattern_category', 8 );
