<?php

declare( strict_types = 1 );

add_action( 'enqueue_block_editor_assets', 'humanity_register_editor_plugins_assets' );

if ( ! function_exists( 'humanity_register_editor_plugins_assets' ) ) {
	/**
	 * Register editor plugins assets
	 *
	 * @return void
	 */
	function humanity_register_editor_plugins_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		$ds = DIRECTORY_SEPARATOR;

		if ( file_exists( __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php' ) ) {
			$data = require_once __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_script(
			'humanity-theme-editor-plugins',
			get_template_directory_uri() . '/build/editor-plugins/index.js',
			$deps,
			$theme->get( 'Version' ),
			true,
		);
	}
}
