<?php

declare( strict_types = 1 );

add_action( 'enqueue_block_editor_assets', 'humanity_register_editor_plugins_assets' );

if ( ! function_exists( 'humanity_register_editor_plugins_assets' ) ) {
	/**
	 * Register editor plugins assets
	 *
	 * @return void
	 */
	function humanity_register_editor_plugins_assets(): void {
		$deps  = [];
		$theme = wp_get_theme();

		$ds = DIRECTORY_SEPARATOR;

		if ( file_exists( __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php' ) ) {
			$data = require_once __DIR__ . $ds . 'editor-plugins' . $ds . 'index.asset.php';
			$deps = $data['dependencies'] ?? [];
		}

		wp_enqueue_script(
			'humanity-theme-editor-plugins',
			get_template_directory_uri() . '/build/editor-plugins/index.js',
			$deps,
			$theme->get( 'Version' ),
			true,
		);

		humanity_localise_editor();
	}
}

if ( ! function_exists( 'humanity_localise_editor' ) ) {
	/**
	 * Localise the editor script with the data it requires
	 *
	 * @return void
	 */
	function humanity_localise_editor(): void {
		#region localisation
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

		wp_localize_script( 'humanity-theme-editor-plugins', 'amnestyCoreI18n', $data );
		#endregion localisation

		#region settings
		$settings = [
			'defaultSidebars' => [
				'post'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ),
				'page'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar_page' ) ),
				'wp_template' => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ), // for the site editor
			],
		];

		$taxonomies = get_taxonomies(
			[
				'amnesty' => true,
				'public'  => true,
			],
			'objects'
		);

		$taxonomies = apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );

		$settings += compact( 'taxonomies' );

		if ( amnesty_taxonomy_is_enabled( 'location' ) ) {
			$settings['locationSlug'] = amnesty_get_taxonomy_slug( 'location' );
		}

		wp_localize_script( 'humanity-theme-editor-plugins', 'aiSettings', $settings );
		#endregion settings

		#region data
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
		#endregion data
	}
}
