<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_disable_emoji_support' ) ) {
	/**
	 * Disable emoji support
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_disable_emoji_support(): void {
		if ( apply_filters( 'amnesty_disable_emoji', true ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
		}
	}
}

add_action( 'wp_loaded', 'amnesty_disable_emoji_support' );
