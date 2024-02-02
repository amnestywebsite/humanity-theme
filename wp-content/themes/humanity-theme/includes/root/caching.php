<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_cache_header' ) ) {
	/**
	 * Add caching header for WP-Engine.
	 *
	 * @package Amnesty\Caching
	 *
	 * @return void
	 */
	function amnesty_cache_header(): void {
		// sanity check.
		if ( headers_sent() ) {
			return;
		}

		// disable in development.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return;
		}

		// disable in non-WPE environment.
		if ( ! defined( 'PWP_NAME' ) ) {
			return;
		}

		// disable for previews.
		// phpcs:ignore
		if ( isset( $_GET['preview'] ) || is_preview() ) {
			return;
		}

		// disable for WP core
		// phpcs:ignore
		if ( preg_match( '/^\/wp-*/', $_SERVER['REQUEST_URI'] ) ) {
			return;
		}

		header( sprintf( 'Cache-Control: max-age=%s, must-revalidate', HOUR_IN_SECONDS * 6 ) );
	}
}

add_action( 'template_redirect', 'amnesty_cache_header' );

// when the object cache for a post is cleared, purge the WPE varnish cache
add_action(
	'clean_post_cache',
	function ( int $post_id ) {
		if ( class_exists( 'WpeCommon' ) ) {
			WpeCommon::purge_varnish_cache( $post_id );
		}
	} 
);
