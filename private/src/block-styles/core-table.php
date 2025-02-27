<?php

add_action( 'init', 'humanity_register_core_table_block_styles' );

if ( ! function_exists( 'humanity_register_core_table_block_styles' ) ) {
	/**
	 * Register block styles for the core table block
	 *
	 * @return void
	 */
	function humanity_register_core_table_block_styles(): void {
		register_block_style(
			'core/table',
			[
				// translators: [admin]
				'name'  => 'responsive',
				'label' => _x( 'Responsive', 'block style', 'amnesty' ),
			],
		);
	}
}
