<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_asset_uri' ) ) {
	/**
	 * Get asset/$folder dir URI.
	 *
	 * @package Amnesty\ThemeSetup
	 * @example echo asset_uri('images');
	 *
	 * @param string $folder Folder.
	 *
	 * @return string
	 */
	function amnesty_asset_uri( $folder ) {
		return get_template_directory_uri() . '/assets/' . $folder;
	}
}

if ( ! function_exists( 'amnesty_admin_styles' ) ) {
	/**
	 * Enqueue admin styles.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_admin_styles() {
		$theme = wp_get_theme();

		wp_enqueue_style( 'theme-admin', amnesty_asset_uri( 'styles' ) . '/admin.css', [], $theme->get( 'Version' ), 'all' );
		wp_enqueue_script( 'theme-admin', amnesty_asset_uri( 'scripts' ) . '/admin.js', [ 'jquery-core', 'lodash' ], $theme->get( 'Version' ), true );
		wp_add_inline_style( 'theme-admin', '.nopad th,.nopad td{padding:0}' );

		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );
		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'theme-admin', $chosen_ol_format );
		}
	}
}

add_action( 'admin_enqueue_scripts', 'amnesty_admin_styles' );


if ( ! function_exists( 'amnesty_styles' ) ) {
	/**
	 * Enqueue styles.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_styles() {
		if ( is_admin() || 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		$theme = wp_get_theme();

		$style_deps = [];

		if ( wp_style_is( 'woocommerce-general' ) ) {
			$style_deps[] = 'woocommerce-general';
		}

		wp_enqueue_style( 'amnesty-theme', amnesty_asset_uri( 'styles' ) . '/bundle.css', $style_deps, $theme->get( 'Version' ), 'all' );

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

		if ( is_singular( 'post' ) ) {
			wp_enqueue_style( 'print-styles', amnesty_asset_uri( 'styles' ) . '/print.css', [], $theme->get( 'Version' ), 'print' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'amnesty_styles', 10 );

if ( ! function_exists( 'amnesty_scripts' ) ) {
	/**
	 * Enqueue scripts.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_scripts() {
		if ( is_admin() || 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		$theme = wp_get_theme();

		wp_register_script( 'flourish-embed', 'https://public.flourish.studio/resources/embed.js', [], $theme->get( 'Version' ), true );
		wp_register_script( 'tickcounter-sdk', 'https://www.tickcounter.com/static/js/loader.js', [], $theme->get( 'Version' ), true );
		wp_enqueue_script( 'infogram-embed', amnesty_asset_uri( 'scripts' ) . '/infogram-loader.js', [], $theme->get( 'Version' ), false );

		wp_enqueue_script( 'amnesty-theme', amnesty_asset_uri( 'scripts' ) . '/bundle.js', [ 'lodash', 'wp-i18n' ], $theme->get( 'Version' ), true );

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

add_action( 'wp_loaded', 'amnesty_scripts' );

if ( ! function_exists( 'amnesty_gutenberg_assets' ) ) {
	/**
	 * Load the Gutenberg assets on the WordPress backend.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_gutenberg_assets() {
		$theme = wp_get_theme();

		wp_enqueue_script(
			'amnesty-core-blocks-js',
			amnesty_asset_uri( 'scripts' ) . '/blocks.js',
			[
				'lodash',
				'wp-blocks',
				'wp-data',
				'wp-edit-post',
				'wp-element',
				'wp-i18n',
				'wp-url',
			],
			$theme->get( 'Version' ),
			false
		);

		wp_add_inline_script( 'amnesty-core-blocks-js', sprintf( 'var WPVersion="%s"', esc_attr( $GLOBALS['wp_version'] ) ), 'before' );
		wp_localize_script( 'amnesty-core-blocks-js', 'userRoles', wp_get_current_user()->roles );
		wp_localize_script( 'amnesty-core-blocks-js', 'postTypes', amnesty_get_post_types() );

		$settings = [
			'petitionForm' => amnesty_feature_is_enabled( 'petitions_form' ),
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

		wp_localize_script( 'amnesty-core-blocks-js', 'aiSettings', $settings );

		if ( current_theme_supports( 'woocommerce' ) ) {
			$woo = [
				'nonce' => wp_create_nonce( 'amnesty-wc' ),
			];

			if ( function_exists( 'amnesty_get_wooccm_fields' ) ) {
				$woo['wooccm'] = amnesty_get_wooccm_fields(
					function ( $field ) {
						return empty( $field['disabled'] ) && 'select' === $field['type'];
					}
				);
			}

			wp_localize_script( 'amnesty-core-blocks-js', 'amnestyWC', $woo );
		}

		wp_set_script_translations( 'amnesty-core-blocks-js', 'amnesty', get_template_directory() . '/languages' );

		wp_enqueue_style( 'amnesty-core-gutenberg', amnesty_asset_uri( 'styles' ) . '/blocks.css', [ 'wp-block-library-theme' ], $theme->get( 'Version' ), 'all' );
	}
}

add_action( 'enqueue_block_editor_assets', 'amnesty_gutenberg_assets' );


if ( ! function_exists( 'amnesty_localise_javascript' ) ) {
	/**
	 * Localise front/backend scripts
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_localise_javascript() {
		if ( ! get_translations_for_domain( 'amnesty' ) ) {
			return;
		}

		$data = [
			/* translators: [front] */
			'listSeparator'    => _x( ', ', 'list item separator', 'amnesty' ),
			/* translators: [front] */
			'openDoubleQuote'  => _x( '“', 'open double quote', 'amnesty' ),
			/* translators: [front] */
			'closeDoubleQuote' => _x( '”', 'close double quote', 'amnesty' ),
			/* translators: [front] */
			'openSingleQuote'  => _x( '‘', 'open single quote', 'amnesty' ),
			/* translators: [front] */
			'closeSingleQuote' => _x( '’', 'close single quote', 'amnesty' ),
			'currentLocale'    => get_locale(),
		];

		wp_localize_script( 'amnesty-theme', 'amnestyCoreI18n', $data );
		wp_localize_script( 'amnesty-core-blocks-js', 'amnestyCoreI18n', $data );
	}
}

add_action( 'enqueue_block_editor_assets', 'amnesty_localise_javascript' );
add_action( 'wp_loaded', 'amnesty_localise_javascript' );

if ( ! function_exists( 'amnesty_trigger_scripts' ) ) {
	/**
	 * Register app trigger with CSP and output in footer
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_trigger_scripts(): void {
		if ( is_admin() || 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		$script = 'App.default();';
		add_filter( 'amnesty_csp_script', fn ( $csp ) => sprintf( "%s 'sha256-%s'", $csp, base64_encode( hash( 'sha256', $script, true ) ) ) );
		add_action( 'wp_footer', fn () => printf( '<script>%s</script>', $script ), 100 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'wp_loaded', 'amnesty_trigger_scripts', 1 );

if ( ! function_exists( 'amnesty_localise_timeinfo' ) ) {
	/**
	 * Get server and setting timezones
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_localise_timeinfo() {
		$wordpress_tz   = wp_timezone();
		$wordpress_time = wp_date( 'Y-m-d H:i:s', null, $wordpress_tz );
		$server_tz      = new DateTimeZone( date_default_timezone_get() );
		$server_time    = wp_date( 'Y-m-d H:i:s', null, $server_tz );

		wp_localize_script(
			'amnesty-core-blocks-js',
			'datetimeInfo',
			[
				'wordpress' => [
					'time' => $wordpress_time,
					'zone' => $wordpress_tz->getName(),
				],
				'server'    => [
					'time' => $server_time,
					'zone' => $server_tz->getName(),
				],
			]
		);
	}
}

add_action( 'enqueue_block_editor_assets', 'amnesty_localise_timeinfo' );

if ( ! function_exists( 'amnesty_enqueue_block_assets' ) ) {
	/**
	 * Enqueue block assets for the full site editor
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_enqueue_block_assets(): void {
		$theme = wp_get_theme();

		wp_enqueue_style( 'amnesty-core-editor', amnesty_asset_uri( 'styles' ) . '/editor.css', [], $theme->get( 'Version' ), 'all' );
	}
}

add_action( 'enqueue_block_assets', 'amnesty_enqueue_block_assets' );

if ( ! function_exists( 'amnesty_disable_cart_fragments' ) ) {
	/**
	 * Disable WooCommerce cart fragments on pages that don't require it
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_disable_cart_fragments() {
		// woocommerce isn't active
		if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
			return;
		}

		// keep it for woocommerce pages
		if ( is_woocommerce() || is_cart() || is_checkout() || is_checkout_pay_page() ) {
			return;
		}

		wp_dequeue_script( 'wc-cart-fragments' );
	}
}

add_action( 'wp_enqueue_scripts', 'amnesty_disable_cart_fragments', 200 );
