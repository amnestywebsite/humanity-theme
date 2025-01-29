<?php

add_action( 'init', 'humanity_register_core_group_block_styles' );

if ( ! function_exists( 'humanity_register_core_group_block_styles' ) ) {
	/**
	 * Register block styles for the core group block
	 *
	 * @return void
	 */
	function humanity_register_core_group_block_styles(): void {
		register_block_style(
			'core/group',
			[
				// translators: [admin]
				'name'  => 'square-border',
				'label' => _x( 'Square Border', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/group',
			[
				// translators: [admin]
				'name'  => 'light',
				'label' => _x( 'Light Background', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/group',
			[
				// translators: [admin]
				'name'  => 'dark',
				'label' => _x( 'Dark Background', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/group',
			[
				// translators: [admin]
				'name'  => 'top-and-bottom-border',
				'label' => _x( 'Top and Bottom Border', 'block style', 'amnesty' ),
			],
		);
	}
}
