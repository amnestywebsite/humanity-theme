<?php

declare( strict_types = 1 );

add_action( 'admin_enqueue_scripts', 'humanity_register_admin_assets' );

if ( ! function_exists( 'humanity_register_admin_assets' ) ) {
	/**
	 * Register admin assets that aren't associated with blocks
	 *
	 * @return void
	 */
	function humanity_register_admin_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		$ds = DIRECTORY_SEPARATOR;

		if ( file_exists( __DIR__ . $ds . 'admin.asset.php' ) ) {
			$data = require_once __DIR__ . $ds . 'admin.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_script( 'theme-admin', get_template_directory_uri() . '/build/admin.js', $deps, $theme->get( 'Version' ), true );
		wp_enqueue_style( 'theme-admin', get_template_directory_uri() . '/build/admin.css', [], $theme->get( 'Version' ) );
		wp_add_inline_style( 'theme-admin', sprintf( ':root{--amnesty-icon-path:url("%s"),none}', esc_url( get_template_directory_uri() . '/images/sprite.svg' ) ) );
	}
}
