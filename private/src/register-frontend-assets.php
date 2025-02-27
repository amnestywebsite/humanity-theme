<?php

declare( strict_types = 1 );

add_action( 'wp_enqueue_scripts', 'humanity_register_frontend_assets' );

if ( ! function_exists( 'humanity_register_frontend_assets' ) ) {
	/**
	 * Register frontend assets that aren't associated with blocks
	 *
	 * @return void
	 */
	function humanity_register_frontend_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		if ( file_exists( __DIR__ . '/frontend/index.asset.php' ) ) {
			$data = require_once __DIR__ . '/frontend/index.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_script( 'amnesty-theme', get_template_directory_uri() . '/build/frontend/index.js', $deps, $theme->get( 'Version' ), true );
		wp_enqueue_style( 'amnesty-theme', get_template_directory_uri() . '/build/frontend/index.css', [], $theme->get( 'Version' ) );
	}
}
