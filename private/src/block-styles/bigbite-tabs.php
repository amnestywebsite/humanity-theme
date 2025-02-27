<?php

add_action( 'init', 'humanity_register_bigbite_tabs_block_styles' );

if ( ! function_exists( 'humanity_register_bigbite_tabs_block_styles' ) ) {
	/**
	 * Register block styles for the Big Bite tabs block
	 *
	 * @return void
	 */
	function humanity_register_bigbite_tabs_block_styles(): void {
		register_block_style(
			'bigbite/tabs',
			[
				// translators: [admin]
				'name'  => 'light',
				'label' => _x( 'Light', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'bigbite/tabs',
			[
				// translators: [admin]
				'name'       => 'grey',
				'label'      => _x( 'Grey', 'block style', 'amnesty' ),
				'is_default' => true,
			],
		);
	}
}
