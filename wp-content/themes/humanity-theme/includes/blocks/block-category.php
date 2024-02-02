<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_core_register_category' ) ) {
	/**
	 * Add a custom category to Gutenberg for easy selection of the custom blocks.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $categories - Current Gutenberg categories.
	 *
	 * @return array
	 */
	function amnesty_core_register_category( array $categories = [] ) {
		array_splice(
			$categories,
			1,
			0,
			[
				[
					'slug'  => 'amnesty-core',
					/* translators: [admin] */
					'title' => __( 'Amnesty Core', 'amnesty' ),
				],
			] 
		);

		return $categories;
	}
}

if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
	add_filter( 'block_categories', 'amnesty_core_register_category', 100 );
} else {
	add_filter( 'block_categories_all', 'amnesty_core_register_category', 100 );
}
