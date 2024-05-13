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
		register_block_pattern_category(
			'humanity',
			[
				/* translators: [admin] */
				'label' => __( 'Humanity Theme', 'amnesty' ),
			]
		);

		register_block_pattern_category(
			'amnesty-core',
			[
				/* translators: [admin] */
				'label' => __( 'Amnesty Core', 'amnesty' ),
			]
		);
	}
}

add_action( 'init', 'amnesty_core_register_pattern_category', 8 );
