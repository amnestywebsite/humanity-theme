<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_remove_generator' ) ) {
	/**
	 * Remove the WP generator meta tag.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_remove_generator(): void {
		$types = apply_filters(
			'amnesty_remove_generator_from',
			[
			// phpcs:ignore
			'html', 'xhtml', 'atom', 'rss2', 'rdf', 'comment',
			] 
		);

		foreach ( $types as $type ) {
			add_filter( sprintf( 'get_the_generator_%s', $type ), '__return_empty_string' );
		}
	}
}

add_action( 'after_setup_theme', 'amnesty_remove_generator' );

remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
