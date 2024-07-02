<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_add_social_icon_block_styles' ) ) {
	/**
	 * Add styles to the core social icons block
	 *
	 * @package Amnesty\CoreBlocks
	 *
	 * @return void
	 */
	function amnesty_add_social_icon_block_styles(): void {
		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Dark', 'block style', 'amnesty' ),
				'name'  => 'dark',
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Dark Circle', 'block style', 'amnesty' ),
				'name'  => 'dark-circle',
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Light', 'block style', 'amnesty' ),
				'name'  => 'light',
			]
		);

		register_block_style(
			'core/social-links',
			[
				'name'  => 'light-circle',
				// translators: [admin]
				'label' => _x( 'Light Circle', 'block style', 'amnesty' ),
			]
		);

		register_block_style(
			'core/social-links',
			[
				'name'  => 'yellow',
				// translators: [admin]
				'label' => _x( 'Yellow', 'block style', 'amnesty' ),
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Yellow Circle', 'block style', 'amnesty' ),
				'name'  => 'yellow-circle',
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Logos Only Dark', 'block style', 'amnesty' ),
				'name'  => 'logos-only-dark',
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Logos Only Light', 'block style', 'amnesty' ),
				'name'  => 'logos-only-light',
			]
		);

		register_block_style(
			'core/social-links',
			[
				// translators: [admin]
				'label' => _x( 'Logos Only Yellow', 'block style', 'amnesty' ),
				'name'  => 'logos-only-yellow',
			]
		);
	}
}

add_action( 'init', 'amnesty_add_social_icon_block_styles' );
