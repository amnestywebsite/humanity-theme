<?php

declare( strict_types = 1 );

add_action( 'enqueue_block_editor_assets', 'humanity_register_editor_assets' );

if ( ! function_exists( 'humanity_register_editor_assets' ) ) {
	/**
	 * Register editor assets that aren't associated with blocks
	 *
	 * @return void
	 */
	function humanity_register_editor_assets(): void {
		$theme = wp_get_theme();

		wp_enqueue_style( 'amnesty-core-gutenberg', get_template_directory_uri() . '/build/editor/index.css', [], $theme->get( 'Version' ) );

		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );

		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'amnesty-core-gutenberg', $chosen_ol_format );
		}

		$open_double  = _x( '“', 'open double quote', 'amnesty' );
		$close_double = _x( '”', 'close double quote', 'amnesty' );
		$open_single  = _x( '‘', 'open single quote', 'amnesty' );
		$close_single = _x( '’', 'close single quote', 'amnesty' );

		$quotes = sprintf( 'blockquote{quotes:\'%s\' \'%s\' "%s" "%s"}', $open_double, $close_double, $open_single, $close_single );
		wp_add_inline_style( 'amnesty-core-gutenberg', $quotes );
	}
}
