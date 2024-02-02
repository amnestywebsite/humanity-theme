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
