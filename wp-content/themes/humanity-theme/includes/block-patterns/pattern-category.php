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
		// types
		register_block_pattern_category( 'humanity-actions', [ 'label' => __( 'Actions', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-media', [ 'label' => __( 'Media', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-templates', [ 'label' => __( 'Templates', 'amnesty' ) ] );

		// layouts
		register_block_pattern_category( 'humanity-two-column', [ 'label' => __( 'Two Column', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-three-column', [ 'label' => __( 'Three Column', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-four-column', [ 'label' => __( 'Four Column', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-halves', [ 'label' => __( '1/2, 1/2', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-thirds', [ 'label' => __( '1/3, 1/3, 1/3', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-quarters', [ 'label' => __( '1/4, 1/4, 1/4, 1/4', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-sixtysix-thirtythree', [ 'label' => __( '2/3, 1/3', 'amnesty' ) ] );
		register_block_pattern_category( 'humanity-thirtythree-sixtysix', [ 'label' => __( '1/3, 2/3', 'amnesty' ) ] );
	}
}

add_action( 'init', 'amnesty_core_register_pattern_category', 8 );
