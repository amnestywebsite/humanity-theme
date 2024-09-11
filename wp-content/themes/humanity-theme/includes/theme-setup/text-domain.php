<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_load_core_textdomain' ) ) {
	/**
	 * Load the theme text domain for all template strings within the content
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_load_core_textdomain() {
		load_theme_textdomain( 'amnesty', get_stylesheet_directory() . '/languages' );
	}
}

add_action( 'after_setup_theme', 'amnesty_load_core_textdomain' );

if ( ! function_exists( 'register_theme_as_translatable' ) ) {
	/**
	 * Register this theme as a translatable package
	 *
	 * @see https://github.com/amnestywebsite/humanity-translation-management
	 *
	 * @param array<int,array<string,string>> $packages existing packages
	 *
	 * @return array<int,array<string,string>>
	 */
	function register_theme_as_translatable( array $packages = [] ): array {
		$packages[] = [
			'id'     => 'humanity-theme',
			'label'  => __( 'Humanity Theme', 'amnesty' ),
			'path'   => get_template_directory(),
			'pot'    => get_template_directory() . '/languages/amnesty.pot',
			'domain' => 'amnesty',
		];

		return $packages;
	}
}

add_filter( 'register_translatable_package', 'register_theme_as_translatable' );
