<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_modify_button_block_metadata' ) ) {
	/**
	 * Modify core button block metadata prior to registration
	 *
	 * Currently only removes core's button styles, so that
	 * we can register our own.
	 *
	 * @package Amnesty
	 *
	 * @param array<string,mixed> $settings the parsed block settings
	 * @param array<string,mixed> $metadata the raw block metadata
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_modify_button_block_metadata( array $settings, array $metadata = [] ): array {
		if ( 'core/button' === $metadata['name'] ) {
			$settings['styles'] = [];
		}

		return $settings;
	}
}

add_filter( 'block_type_metadata_settings', 'amnesty_modify_button_block_metadata', 10, 2 );

if ( ! function_exists( 'amnesty_add_button_block_styles' ) ) {
	/**
	 * Add styles to the core button block
	 *
	 * @package Amnesty\CoreBlocks
	 *
	 * @return void
	 */
	function amnesty_add_button_block_styles(): void {
		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'label' => _x( 'Dark', 'block style', 'amnesty' ),
				'name'  => 'dark',
			]
		);

		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'label' => _x( 'Light', 'block style', 'amnesty' ),
				'name'  => 'light',
			]
		);

		register_block_style(
			'core/button',
			[
				// translators: [admin]
				'label' => _x( 'Back Link', 'block style', 'amnesty' ),
				'name'  => 'link',
			]
		);
	}
}

add_action( 'init', 'amnesty_add_button_block_styles' );
