<?php

declare( strict_types = 1 );

namespace Amnesty;

new Options_Manager();

/**
 * Options API wrapper
 *
 * This primarily exists to allow older versions of the
 * Humanity Theme and associated plugins to retain their
 * options registration via CMB2.
 */
class Options_Manager {

	/**
	 * The top level options menu page slug
	 *
	 * @var string
	 */
	public static string $menu_slug = 'humanity-options';

	/**
	 * The main option key
	 *
	 * @var string
	 */
	public static string $option_key = 'amnesty_theme_options_page';

	/**
	 * Construct the options manager
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'boot' ], 1 );
	}

	/**
	 * Boot our settings/options
	 *
	 * @return void
	 */
	public function boot(): void {
		$hook = add_menu_page(
			// translators: [admin]
			__( 'Theme Options v2', 'amnesty' ),
			// translators: [admin]
			__( 'Theme Options v2', 'amnesty' ),
			'manage_options',
			static::$menu_slug,
			[ $this, 'render_top_level_options_page' ],
			'dashicons-admin-generic',
			999,
		);

		register_setting(
			static::$option_key,
			static::$option_key,
			[
				'type'              => 'array',
				'description'       => __( 'General options for the Humanity theme', 'amnesty' ),
				'show_in_rest'      => false,
				'sanitize_callback' => [ $this, 'sanitise_option' ],
			]
		);

		do_action( 'amnesty_register_settings_sections', static::$option_key );

		add_action( 'load-' . $hook, [ $this, 'process_top_level_form_submission' ] );

		// add_settings_section( 'sid', 'name', function () {}, 'slug' );
		// add_settings_field( 'fid', 'label', function () {}, 'slug', 'sid', args: [] ;)
	}

	/**
	 * Render the top level options page
	 *
	 * @return void
	 */
	public function render_top_level_options_page(): void {
		require_once __DIR__ . '/views/theme-options-page.php';
	}

	/**
	 * Called on a POST request to save updated options data
	 *
	 * @return void
	 */
	public function process_top_level_form_submission(): void {
		if ( 'POST' !== strtoupper( sanitize_key( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) ) ) {
			return;
		}

		check_admin_referer( static::$menu_slug . '_submit' );

		// validate, sanitise, and save
	}

	/**
	 * Sanitise user-submitted data for the option
	 *
	 * @return void
	 */
	public function sanitise_option() {
	}

}
