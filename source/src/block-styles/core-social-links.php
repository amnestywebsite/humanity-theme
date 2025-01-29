<?php

add_action( 'init', 'humanity_register_core_social_links_block_styles' );

if ( ! function_exists( 'humanity_register_core_social_links_block_styles' ) ) {
	/**
	 * Register block styles for the core button block
	 *
	 * @return void
	 */
	function humanity_register_core_social_links_block_styles(): void {
		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'dark',
				'label' => _x( 'Dark', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'dark-circle',
				'label' => _x( 'Dark Circle', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'light',
				'label' => _x( 'Light', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'light-circle',
				'label' => _x( 'Light Circle', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'light-circle',
				'label' => _x( 'Light Circle', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'logos-only-dark',
				'label' => _x( 'Logos Only Dark', 'block style', 'amnesty' ),
			],
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'name'  => 'logos-only-light',
				'label' => _x( 'Logos Only Light', 'block style', 'amnesty' ),
			],
		);
	}
}
