<?php

declare( strict_types = 1 );

add_action( 'enqueue_block_editor_assets', 'humanity_register_shared_assets' );
add_action( 'wp_enqueue_scripts', 'humanity_register_shared_assets' );

if ( ! function_exists( 'humanity_register_shared_assets' ) ) {
	/**
	 * Register assets that are shared between the editor and frontend
	 *
	 * @return void
	 */
	function humanity_register_shared_assets(): void {
		$theme = wp_get_theme();
		$deps  = [];

		wp_register_script( 'flourish-embed', 'https://public.flourish.studio/resources/embed.js', [], $theme->get( 'Version' ), true );
		wp_register_script( 'tickcounter-sdk', 'https://www.tickcounter.com/static/js/loader.js', [], $theme->get( 'Version' ), true );

		$ds = DIRECTORY_SEPARATOR;

		if ( file_exists( __DIR__ . $ds . 'shared.asset.php' ) ) {
			$data = require_once __DIR__ . $ds . 'shared.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_style( 'humanity-theme-shared', get_template_directory_uri() . '/build/shared.css', $deps, $theme->get( 'Version' ) );
	}
}
