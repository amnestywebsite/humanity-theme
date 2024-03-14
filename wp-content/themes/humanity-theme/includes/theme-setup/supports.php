<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_theme_support' ) ) {
	/**
	 * Setup theme support.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_theme_support() {
		add_theme_support( 'menus' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', [ 'script', 'style' ] );

		remove_theme_support( 'post-formats' );

		add_theme_support( 'editor-color-palette', [] );
		add_theme_support( 'editor-gradient-presets', [] );
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-gradients' );
		add_theme_support( 'disable-custom-font-sizes' );

		add_theme_support( 'block-template-parts' );

		if ( class_exists( 'WooCommerce', false ) ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
}

add_action( 'after_setup_theme', 'amnesty_theme_support' );

if ( ! function_exists( 'amnesty_page_type_support' ) ) {
	/**
	 * Add excerpt support to pages to use on the grid blocks.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_page_type_support() {
		add_post_type_support( 'page', 'excerpt' );
	}
}

add_action( 'init', 'amnesty_page_type_support' );

if ( ! function_exists( 'amnesty_post_tag_support' ) ) {
	/**
	 * Remove tags support
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_post_tag_support() {
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );
	}
}

add_action( 'init', 'amnesty_post_tag_support' );

/**
 * Rename default template for clarity.
 */
add_filter(
	'default_page_template_title',
	function () {
		/* translators: [admin] */
		return __( 'Standard Post Template', 'amnesty' );
	}
);

// disable old XML-RPC "API"
add_filter( 'xmlrpc_enabled', '__return_false' );
