<?php

declare( strict_types = 1 );

namespace Amnesty;

use Algolia_Plugin;
use Algolia_Plugin_Factory;
use DateTimeZone;
use WP_Theme;

/**
 * Handles asset loading for the theme
 */
class Asset_Loader {

	/**
	 * Theme object
	 *
	 * @var \WP_Theme
	 */
	protected WP_Theme $theme;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'boot' ] );
	}

	/**
	 * Boot the class
	 *
	 * @return void
	 */
	public function boot(): void {
		$this->theme = wp_get_theme();
		$this->add_hooks();
	}

	/**
	 * Register necessary actions
	 *
	 * @return void
	 */
	protected function add_hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_localisation' ] );

		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_localisation' ] );

		add_action( 'enqueue_block_assets', [ $this, 'site_editor_styles' ] );
		add_action( 'enqueue_block_assets', [ $this, 'site_editor_scripts' ] );
		add_action( 'enqueue_block_assets', [ $this, 'site_editor_localisation' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'front_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'front_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'front_localisation' ] );
	}

	/**
	 * Enqueue admin area styles
	 *
	 * @return void
	 */
	public function admin_styles(): void {
		wp_enqueue_style( 'theme-admin', amnesty_asset_uri( 'styles' ) . '/admin.css', [], $this->theme->get( 'Version' ), 'all' );
		wp_add_inline_style( 'theme-admin', sprintf( ':root{--amnesty-icon-path:url("%s"),none}', esc_url( get_template_directory_uri() . '/assets/images/sprite.svg' ) ) );
		wp_add_inline_style( 'theme-admin', '.nopad th,.nopad td{padding:0}' );

		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );
		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'theme-admin', $chosen_ol_format );
		}
	}

	/**
	 * Enqueue admin area scripts
	 *
	 * @return void
	 */
	public function admin_scripts(): void {
		$deps = [ 'jquery-core', 'lodash' ];

		wp_enqueue_script( 'theme-admin', amnesty_asset_uri( 'scripts' ) . '/admin.js', $deps, $this->theme->get( 'Version' ), true );
	}

	/**
	 * Localise admin area scripts
	 *
	 * @return void
	 */
	public function admin_localisation(): void {
	}

	/**
	 * Enqueue block editor styles
	 *
	 * @return void
	 */
	public function block_editor_styles(): void {
		wp_enqueue_style( 'amnesty-core-gutenberg', amnesty_asset_uri( 'styles' ) . '/blocks.css', [ 'wp-block-library-theme' ], $this->theme->get( 'Version' ), 'all' );
		wp_add_inline_style( 'amnesty-core-gutenberg', sprintf( ':root{--amnesty-icon-path:url("%s"),none}', esc_url( get_template_directory_uri() . '/assets/images/sprite.svg' ) ) );
	}

	/**
	 * Enqueue block editor area scripts
	 *
	 * @return void
	 */
	public function block_editor_scripts(): void {
		$deps = [ 'lodash', 'wp-blocks', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-url' ];

		wp_enqueue_script( 'amnesty-core-blocks-js', amnesty_asset_uri( 'scripts' ) . '/blocks.js', $deps, $this->theme->get( 'Version' ), false );
		wp_add_inline_script( 'amnesty-core-blocks-js', sprintf( 'var WPVersion="%s"', esc_attr( $GLOBALS['wp_version'] ) ), 'before' );
	}

	/**
	 * Localise block editor area scripts
	 *
	 * @return void
	 */
	public function block_editor_localisation(): void {
		wp_localize_script( 'amnesty-core-blocks-js', 'userRoles', wp_get_current_user()->roles );
		wp_localize_script( 'amnesty-core-blocks-js', 'postTypes', amnesty_get_post_types() );

		$settings = wp_cache_get( __FUNCTION__ . get_current_blog_id() . get_locale() );

		if ( ! is_array( $settings ) ) {
			$settings = [
				'petitionForm'    => amnesty_feature_is_enabled( 'petitions_form' ),
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

			wp_cache_add( __FUNCTION__ . get_current_blog_id() . get_locale(), $settings );
		}

		wp_localize_script( 'amnesty-core-blocks-js', 'aiSettings', $settings );
		wp_set_script_translations( 'amnesty-core-blocks-js', 'amnesty', get_template_directory() . '/languages' );

		$wordpress_tz   = wp_timezone();
		$wordpress_time = wp_date( 'Y-m-d H:i:s', null, $wordpress_tz );
		$server_tz      = new DateTimeZone( date_default_timezone_get() );
		$server_time    = wp_date( 'Y-m-d H:i:s', null, $server_tz );
		$timezone_info  = [
			'wordpress' => [
				'time' => $wordpress_time,
				'zone' => $wordpress_tz->getName(),
			],
			'server'    => [
				'time' => $server_time,
				'zone' => $server_tz->getName(),
			],
		];

		wp_localize_script( 'amnesty-core-blocks-js', 'datetimeInfo', $timezone_info );

		$this->script_internationalisation( 'amnesty-core-blocks-js' );
	}

	/**
	 * Enqueue site editor styles
	 *
	 * @return void
	 */
	public function site_editor_styles(): void {
		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_style( 'amnesty-core-editor', amnesty_asset_uri( 'styles' ) . '/editor.css', [], $this->theme->get( 'Version' ), 'all' );
	}

	/**
	 * Enqueue site editor scripts
	 *
	 * @return void
	 */
	public function site_editor_scripts(): void {
	}

	/**
	 * Localise site editor scripts
	 *
	 * @return void
	 */
	public function site_editor_localisation(): void {
	}

	/**
	 * Enqueue frontend styles
	 *
	 * @return void
	 */
	public function front_styles(): void {
		wp_enqueue_style( 'amnesty-theme', amnesty_asset_uri( 'styles' ) . '/bundle.css', [], $this->theme->get( 'Version' ), 'all' );
		wp_add_inline_style( 'amnesty-theme', sprintf( ':root{--amnesty-icon-path:url("%s"),none}', esc_url( get_template_directory_uri() . '/assets/images/sprite.svg' ) ) );

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

		wp_enqueue_style( 'print-styles', amnesty_asset_uri( 'styles' ) . '/print.css', [], $this->theme->get( 'Version' ), 'print' );
	}

	/**
	 * Enqueue frontend area scripts
	 *
	 * @return void
	 */
	public function front_scripts(): void {
		$deps = [ 'lodash', 'wp-i18n' ];

		$should_load_algolia = class_exists( 'Algolia_Plugin_Factory' ) && ( is_search() || get_queried_object_id() === absint( get_option( 'amnesty_search_page' ) ) );

		if ( $should_load_algolia ) {
			Algolia_Plugin_Factory::create()->get_template_loader()->load_algolia_config();

			$deps[] = 'algolia-search';
			$deps[] = 'algolia-instantsearch';
			$deps[] = 'algolia-autocomplete';
			$deps[] = 'algolia-autocomplete-noconflict';
		}

		wp_register_script( 'flourish-embed', 'https://public.flourish.studio/resources/embed.js', [], $this->theme->get( 'Version' ), true );
		wp_register_script( 'tickcounter-sdk', 'https://www.tickcounter.com/static/js/loader.js', [], $this->theme->get( 'Version' ), true );
		wp_enqueue_script( 'infogram-embed', amnesty_asset_uri( 'scripts' ) . '/infogram-loader.js', [], $this->theme->get( 'Version' ), false );

		wp_enqueue_script( 'amnesty-theme', amnesty_asset_uri( 'scripts' ) . '/bundle.js', $deps, $this->theme->get( 'Version' ), true );
		wp_add_inline_script( 'amnesty-theme', 'App.default();', 'after' );
	}

	/**
	 * Localise frontend area scripts
	 *
	 * @return void
	 */
	public function front_localisation(): void {
		$settings = wp_cache_get( __FUNCTION__ . get_current_blog_id() . get_locale() );

		if ( ! is_array( $settings ) ) {
			$settings = [
				'archive_base_url' => get_pagenum_link( 1, false ),
				'domain'           => wp_parse_url( home_url( '/', 'https' ), PHP_URL_HOST ),
				'pop_in_timeout'   => 30,
				'active_pop_in'    => 0,
			];

			$pop_in = get_option( 'amnesty_pop_in_options_page' );

			if ( ! empty( $pop_in ) ) {
				$settings = array_merge( $settings, $pop_in );
			}

			wp_cache_add( __FUNCTION__ . get_current_blog_id() . get_locale(), $settings );
		}

		wp_localize_script( 'amnesty-theme', 'amnesty_data', $settings );

		$should_load_algolia = class_exists( 'Algolia_Plugin_Factory' ) && ( is_search() || get_queried_object_id() === absint( get_option( 'amnesty_search_page' ) ) );
		if ( $should_load_algolia ) {
			$this->algolia_data( 'amnesty-theme' );
		}

		$this->script_internationalisation( 'amnesty-theme' );
	}

	/**
	 * Localise a script handle with i18n data
	 *
	 * @param string $script_handle the script handle
	 *
	 * @return void
	 */
	protected function script_internationalisation( string $script_handle ): void {
		if ( ! get_translations_for_domain( 'amnesty' ) ) {
			return;
		}

		$cache_key = __FUNCTION__ . $script_handle . get_current_blog_id() . get_locale();
		$i18n_data = wp_cache_get( $cache_key );

		if ( ! is_array( $i18n_data ) ) {
			$i18n_data = [
				/* translators: [front] */
				'listSeparator'    => _x( ',', 'list item separator', 'amnesty' ),
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

			$options = get_option( 'amnesty_localisation_options_page' );
			if ( isset( $options['enforce_grouping_separators'] ) ) {
				$i18n_data['enforceGroupingSeparators'] = 'on' === $options['enforce_grouping_separators'];
			}

			wp_cache_add( $cache_key, $i18n_data );
		}

		wp_localize_script( $script_handle, 'amnestyCoreI18n', $i18n_data );
	}

	/**
	 * Localise a script handle with Algolia search data
	 *
	 * @param string $script_handle the script handle
	 *
	 * @return void
	 */
	protected function algolia_data( string $script_handle ): void {
		$algolia_data = wp_cache_get( __FUNCTION__ . 'algolia' . get_current_blog_id() . get_locale() );

		$category_slug = get_option( 'amnesty_category_slug' ) ?: 'category';
		$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';
		$topic_slug    = get_option( 'amnesty_topic_slug' ) ?: 'topic';

		if ( ! is_array( $algolia_data ) ) {
			$algolia_data = [
				$category_slug => $this->format_terms_for_algolia( (array) get_terms( [ 'taxonomy' => $category_slug ] ) ),
				$location_slug => $this->format_terms_for_algolia( (array) get_terms( [ 'taxonomy' => $location_slug ] ) ),
				$topic_slug    => $this->format_terms_for_algolia( (array) get_terms( [ 'taxonomy' => $topic_slug ] ) ),
				'months'       => amnesty_get_months(),
				'locale'       => get_locale(),
			];

			wp_cache_add( __FUNCTION__ . get_current_blog_id() . get_locale(), $algolia_data );
		}

		wp_localize_script( $script_handle, 'AlgoliaSearch', $algolia_data );
	}

	/**
	 * Format taxonomy terms for use in Algolia search
	 *
	 * @param array<int,\WP_Term> $terms the terms to format
	 *
	 * @return array<string,array<string,mixed>>
	 */
	protected function format_terms_for_algolia( array $terms ): array {
		$formatted = [];

		foreach ( $terms as $term ) {
			$formatted[ $term->name ] = [
				...(array) $term,
				'permalink' => get_term_link( $term ),
			];
		}

		return $formatted;
	}

}
