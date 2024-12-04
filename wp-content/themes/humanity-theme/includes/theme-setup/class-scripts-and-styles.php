<?php

// phpcs:disable PEAR.Commenting.InlineComment.WrongStyle,Squiz.Commenting.InlineComment.WrongStyle -- this file uses regions

declare( strict_types = 1 );

namespace Amnesty;

use WP_Theme;

/**
 * Asset loadet
 */
class Scripts_And_Styles {

	/**
	 * Theme data
	 *
	 * @var WP_Theme
	 */
	protected WP_Theme $theme;

	/**
	 * Theme version
	 *
	 * @var string
	 */
	protected string $version;

	/**
	 * Bind hooks
	 */
	public function __construct() {
		add_action( 'wp_loaded', [ $this, 'boot' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'disable_cart_fragments' ], 200 );
		add_action( 'enqueue_block_assets', [ $this, 'site_editor_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );
	}

	/**
	 * Provide data for use here
	 *
	 * @return void
	 */
	public function boot(): void {
		$this->theme   = wp_get_theme();
		$this->version = $this->theme->get( 'Version' );
	}

	#region admin

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function admin_assets(): void {
		wp_enqueue_style( 'theme-admin', amnesty_asset_uri( 'styles' ) . '/admin.css', [], $this->version, 'all' );
		wp_enqueue_script( 'theme-admin', amnesty_asset_uri( 'scripts' ) . '/admin.js', [ 'jquery-core', 'lodash' ], $this->version, true );
		wp_add_inline_style( 'theme-admin', '.nopad th,.nopad td{padding:0}' );

		$this->localise_admin_scripts();
	}

	/**
	 * Add localisation data to admin assets
	 *
	 * @return void
	 */
	protected function localise_admin_scripts(): void {
		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );
		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'theme-admin', $chosen_ol_format );
		}
	}

	#endregion admin

	#region frontend

	/**
	 * Enqueue frontend assets
	 *
	 * @return void
	 */
	public function frontend_assets(): void {
		// don't enqueue anywhere other than the frontend
		if ( is_admin() || 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		$style_deps = $this->frontend_style_dependencies();

		wp_enqueue_style( 'amnesty-theme', amnesty_asset_uri( 'styles' ) . '/bundle.css', $style_deps, $this->version, 'all' );

		if ( is_singular( 'post' ) ) {
			wp_enqueue_style( 'print-styles', amnesty_asset_uri( 'styles' ) . '/print.css', [], $this->version, 'print' );
		}

		$this->localise_frontend_styles();

		wp_register_script( 'flourish-embed', 'https://public.flourish.studio/resources/embed.js', [], $this->version, true );
		wp_register_script( 'tickcounter-sdk', 'https://www.tickcounter.com/static/js/loader.js', [], $this->version, true );
		wp_enqueue_script( 'infogram-embed', amnesty_asset_uri( 'scripts' ) . '/infogram-loader.js', [], $this->version, false );

		wp_enqueue_script( 'amnesty-theme', amnesty_asset_uri( 'scripts' ) . '/bundle.js', [ 'lodash', 'wp-i18n' ], $this->version, true );
		wp_add_inline_script( 'amnesty-theme', 'App.default();' );

		$this->localise_frontend_scripts();
		$this->localise_script_with_i18n_data( 'amnesty-theme' );
	}

	/**
	 * Retrieve frontend stylesheet dependencies
	 *
	 * @return array<int,string>
	 */
	protected function frontend_style_dependencies(): array {
		$deps = [];

		if ( wp_style_is( 'woocommerce-general' ) ) {
			$deps[] = 'woocommerce-general';
		}

		return $deps;
	}

	/**
	 * Enqueue frontend l10n styles
	 *
	 * @return void
	 */
	protected function localise_frontend_styles(): void {
		$ol_characters = amnesty_get_option( 'ol_locale_option', 'amnesty_localisation_options_page' );

		if ( $ol_characters ) {
			$chosen_ol_format = sprintf( 'ol{list-style-type:%s;}', $ol_characters );
			wp_add_inline_style( 'amnesty-theme', $chosen_ol_format );
		}

		$quotes = vsprintf(
			'blockquote{quotes:\'%s\' \'%s\' "%s" "%s"}',
			array_values( $this->get_quote_style() ),
		);

		wp_add_inline_style( 'amnesty-theme', $quotes );
	}

	/**
	 * Add localisation data to frontend scripts
	 *
	 * @return void
	 */
	protected function localise_frontend_scripts(): void {
		$data = [
			'archive_base_url' => get_pagenum_link( 1, false ),
			'domain'           => wp_parse_url( home_url( '/', 'https' ), PHP_URL_HOST ),
			'pop_in_timeout'   => 30,
			'active_pop_in'    => 0,
		];

		$pop_in = get_option( 'amnesty_pop_in_options_page' );

		if ( ! empty( $pop_in ) ) {
			$data = array_merge( $data, $pop_in );
		}

		wp_localize_script( 'amnesty-theme', 'amnesty_data', $data );
	}

	/**
	 * Disable WooCommerce cart fragments on pages that don't require it
	 *
	 * @return void
	 */
	public function disable_cart_fragments(): void {
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

	#endregion frontend

	#region blocks
	/**
	 * Enqueue site editor assets
	 *
	 * @return void
	 */
	public function site_editor_assets(): void {
		if ( ! is_admin() ) {
			return;
		}

		wp_enqueue_style( 'amnesty-core-editor', amnesty_asset_uri( 'styles' ) . '/editor.css', [], $this->version, 'all' );
	}
	#endregion blocks

	#region blockEditor

	/**
	 * Enqueue Block Editor assets
	 *
	 * @return void
	 */
	public function block_editor_assets(): void {
		wp_enqueue_style( 'amnesty-core-gutenberg', amnesty_asset_uri( 'styles' ) . '/blocks.css', [ 'wp-block-library-theme' ], $this->version, 'all' );

		$script_deps = $this->block_editor_script_dependencies();
		wp_enqueue_script( 'amnesty-core-blocks-js', amnesty_asset_uri( 'scripts' ) . '/blocks.js', $script_deps, $this->version, true );
		wp_set_script_translations( 'amnesty-core-blocks-js', 'amnesty', get_template_directory() . '/languages' );

		$this->localise_block_editor_scripts();
		$this->localise_script_with_i18n_data( 'amnesty-core-blocks-js' );
	}

	/**
	 * Retrieve block editor script dependencies
	 *
	 * @return array<int,string>
	 */
	protected function block_editor_script_dependencies(): array {
		return [
			'lodash',
			'wp-blocks',
			'wp-data',
			'wp-dom',
			'wp-edit-post',
			'wp-element',
			'wp-i18n',
			'wp-url',
		];
	}

	/**
	 * Add localisation data to block editor scripts
	 *
	 * @return void
	 */
	protected function localise_block_editor_scripts(): void {
		wp_localize_script( 'amnesty-core-blocks-js', 'postTypes', amnesty_get_post_types() );

		$settings = [
			'petitionForm'    => amnesty_feature_is_enabled( 'petitions_form' ),
			'defaultSidebars' => $this->get_default_sidebars(),
			'taxonomies'      => $this->get_related_content_taxonomies(),
		];

		if ( amnesty_taxonomy_is_enabled( 'location' ) ) {
			$settings['locationSlug'] = amnesty_get_taxonomy_slug( 'location' );
		}

		wp_localize_script( 'amnesty-core-blocks-js', 'aiSettings', $settings );

		$this->localise_block_editor_scripts_woocommerce();
	}

	/**
	 * Add localisation data for WooCommerce to block editor scripts
	 *
	 * @return void
	 */
	protected function localise_block_editor_scripts_woocommerce(): void {
		if ( ! current_theme_supports( 'woocommerce' ) ) {
			return;
		}

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

	/**
	 * Retrieve list of default sidebar IDs for post types
	 *
	 * @return array<string,array<int,int>>
	 */
	protected function get_default_sidebars(): array {
		return [
			'post'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ),
			'page'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar_page' ) ),
			'wp_template' => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ), // for the site editor
		];
	}

	/**
	 * Retrieve taxonomies used in Related Content block
	 *
	 * @return array<string,WP_Taxonomy>
	 */
	protected function get_related_content_taxonomies(): array {
		$taxonomies = get_taxonomies(
			[
				'amnesty' => true,
				'public'  => true,
			],
			'objects'
		);

		return (array) apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );
	}

	#endregion blockEditor

	#region shared

	/**
	 * Add localisation data to script with specified handle
	 *
	 * @param string $handle the script handle
	 *
	 * @return void
	 */
	protected function localise_script_with_i18n_data( string $handle ): void {
		if ( ! get_translations_for_domain( 'amnesty' ) ) {
			return;
		}

		$data = [
			'currentLocale' => get_locale(),
			'listSeparator' => _x( ',', 'list item separator', 'amnesty' ),
			...$this->get_quote_style(),
		];

		wp_localize_script( $handle, 'amnestyCoreI18n', $data );
	}

	/**
	 * Retrieve localised quotation marks
	 *
	 * @return array<string,string>
	 */
	protected function get_quote_style(): array {
		return [
			'openDoubleQuote'  => esc_attr_x( '“', 'open double quote', 'amnesty' ),
			'closeDoubleQuote' => esc_attr_x( '”', 'close double quote', 'amnesty' ),
			'openSingleQuote'  => esc_attr_x( '‘', 'open single quote', 'amnesty' ),
			'closeSingleQuote' => esc_attr_x( '’', 'close single quote', 'amnesty' ),
		];
	}

	#endregion shared

}
