<?php

declare( strict_types = 1 );

add_action( 'enqueue_block_editor_assets', 'humanity_register_editor_assets' );

if ( ! function_exists( 'humanity_register_editor_assets' ) ) {
	/**
	 * Register editor assets that aren't associated with blocks
	 *
	 * @return void
	 */
	function humanity_register_editor_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		$ds = DIRECTORY_SEPARATOR;

		if ( file_exists( __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php' ) ) {
			$data = require_once __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_style( 'humanity-theme-editor', get_template_directory_uri() . '/build/editor/index.css', $deps, $theme->get( 'Version' ) );
	}
}
