<?php

declare( strict_types = 1 );

add_action( 'wp_enqueue_scripts', 'humanity_register_frontend_assets' );

if ( ! function_exists( 'humanity_register_frontend_assets' ) ) {
	/**
	 * Register frontend assets that aren't associated with blocks
	 *
	 * @return void
	 */
	function humanity_register_frontend_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		if ( file_exists( __DIR__ . '/frontend/index.asset.php' ) ) {
			$data = require_once __DIR__ . '/frontend/index.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_script( 'amnesty-theme', get_template_directory_uri() . '/build/frontend/index.js', $deps, $theme->get( 'Version' ), true );
		wp_enqueue_style( 'amnesty-theme', get_template_directory_uri() . '/build/frontend/index.css', [], $theme->get( 'Version' ) );
		wp_add_inline_style( 'amnesty-theme', sprintf( ':root{--amnesty-icon-path:url("%s"),none}', esc_url( get_template_directory_uri() . '/images/sprite.svg' ) ) );

		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );

		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'amnesty-theme', $chosen_ol_format );
		}

		$open_double  = _x( '“', 'open double quote', 'amnesty' );
		$close_double = _x( '”', 'close double quote', 'amnesty' );
		$open_single  = _x( '‘', 'open single quote', 'amnesty' );
		$close_single = _x( '’', 'close single quote', 'amnesty' );

		$quotes = sprintf( 'blockquote{quotes:\'%s\' \'%s\' "%s" "%s"}', $open_double, $close_double, $open_single, $close_single );
		wp_add_inline_style( 'amnesty-theme', $quotes );
	}
}

if ( ! function_exists( 'humanity_localise_frontend' ) ) {
	/**
	 * Localise the frontend script with the data it requires
	 *
	 * @return void
	 */
	function humanity_localise_frontend(): void {
		$data = [
			'listSeparator'    => _x( ',', 'list item separator', 'amnesty' ),
			'openDoubleQuote'  => _x( '“', 'open double quote', 'amnesty' ),
			'closeDoubleQuote' => _x( '”', 'close double quote', 'amnesty' ),
			'openSingleQuote'  => _x( '‘', 'open single quote', 'amnesty' ),
			'closeSingleQuote' => _x( '’', 'close single quote', 'amnesty' ),
			'currentLocale'    => get_locale(),
		];

		$options = get_option( 'amnesty_localisation_options_page' );
		if ( isset( $options['enforce_grouping_separators'] ) ) {
			$data['enforceGroupingSeparators'] = 'on' === $options['enforce_grouping_separators'];
		}

		wp_localize_script( 'amnesty-theme', 'amnestyCoreI18n', $data );

		$localise_with = [
			'archive_base_url' => get_pagenum_link( 1, false ),
			'domain'           => wp_parse_url( home_url( '/', 'https' ), PHP_URL_HOST ),
			'pop_in_timeout'   => 30,
			'active_pop_in'    => 0,
		];

		$pop_in = get_option( 'amnesty_pop_in_options_page' );

		if ( ! empty( $pop_in ) ) {
			$localise_with = array_merge( $localise_with, $pop_in );
		}

		wp_localize_script( 'amnesty-theme', 'amnesty_data', $localise_with );
	}
}
