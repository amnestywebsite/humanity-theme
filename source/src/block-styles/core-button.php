<?php

add_action( 'init', 'humanity_register_core_button_block_styles' );

if ( ! function_exists( 'humanity_register_core_button_block_styles' ) ) {
	/**
	 * Register block styles for the core button block
	 *
	 * @return void
	 */
	function humanity_register_core_button_block_styles(): void {
		unregister_block_style( 'core/button', 'fill' );
		unregister_block_style( 'core/button', 'outline' );

		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'name'  => 'dark',
				'label' => _x( 'Dark', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'name'  => 'link',
				'label' => _x( 'Back link', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'name'  => 'search',
				'label' => _x( 'Search', 'block style', 'amnesty' ),
			],
		);
	}
}
