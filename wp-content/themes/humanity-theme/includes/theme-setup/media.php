<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_image_quality' ) ) {
	/**
	 * Stop WP from ruining JPGs
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return int
	 */
	function amnesty_image_quality() {
		return 100;
	}
}

if ( ! function_exists( 'amnesty_add_jfif_support' ) ) {
	/**
	 * Add support for JFIF images
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param array<string,string> $mimes existing list of mime types
	 *
	 * @return array<string,string>
	 */
	function amnesty_add_jfif_support( array $mimes ): array {
		$mimes['jfif'] = 'image/jpeg';
		return $mimes;
	}
}

if ( ! function_exists( 'amnesty_remove_gutenberg_media_options' ) ) {
	/**
	 * Remove entries from the "Media" tab in the Gutenberg inserter
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param array<string,mixed> $settings the block editor settings
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_remove_gutenberg_media_options( array $settings ): array {
		$settings['enableOpenverseMediaCategory'] = false;
		return $settings;
	}
}

add_filter( 'jpeg_quality', 'amnesty_image_quality' );
add_filter( 'wp_editor_set_quality', 'amnesty_image_quality' );

remove_filter( 'the_content', 'prepend_attachment' );

add_action( 'after_setup_theme', 'amnesty_theme_image_sizes' );
add_filter( 'image_size_names_choose', 'amnesty_custom_image_sizes' );

add_filter( 'mime_types', 'amnesty_add_jfif_support' );

add_filter( 'block_editor_settings_all', 'amnesty_remove_gutenberg_media_options' );
