<?php

declare( strict_types = 1 );

namespace Amnesty;

new Fonts();

/**
 * Register custom font support
 *
 * @package Amnesty\Admin\Options
 */
class Fonts {

	/**
	 * Options page parent slug
	 *
	 * @var string
	 */
	protected static $parent = 'amnesty_theme_options_page';

	/**
	 * Option group
	 *
	 * @var string
	 */
	protected static $group = 'amnesty_theme_options';

	/**
	 * Options display callback
	 *
	 * @var string
	 */
	protected static $display = 'amnesty_options_display_with_tabs';

	/**
	 * Option key
	 *
	 * @var string
	 */
	protected static $option = 'amnesty_font_options';

	/**
	 * Add hooks
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front' ], 20 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );
		add_action( 'cmb2_admin_init', [ $this, 'register_options' ] );
	}

	/**
	 * Generate front-end stylesheets
	 *
	 * @return void
	 */
	public function enqueue_front() {
		$settings = get_option( static::$option );
		$settings = wp_parse_args(
			$settings,
			[
				'font_load_type' => 'local',
			] 
		);

		$url = '';
		if ( 'remote' === $settings['font_load_type'] ) {
			$url = $settings['font_face_url'];
		}

		if ( ! $url ) {
			return;
		}

		if ( ! empty( wp_styles()->registered['global-styles']->extra['after'][0] ) ) {
			$css = &wp_styles()->registered['global-styles']->extra['after'][0];

			if ( false !== strpos( $css, '--font-family-primary' ) ) {
				$css = '';
			}
		}

		wp_enqueue_style( 'amnesty-fonts', $url, [ 'global-styles' ], '1.0.0', 'all' );
		wp_add_inline_style(
			'amnesty-fonts',
			sprintf(
				':root{--font-family-primary:"%s",sans-serif;--font-family-secondary:"%s",sans-serif;}',
				$settings['font_family_primary'],
				$settings['font_family_secondary']
			) 
		);
	}

	/**
	 * Enqueue JS
	 *
	 * @return void
	 */
	public function enqueue_admin() {
		if ( 'theme-options_page_amnesty_font_options' !== get_current_screen()->base ) {
			return;
		}

		wp_enqueue_script(
			'amnesty-cmb2-font-options',
			get_template_directory_uri() . '/assets/scripts/cmb2-font-options.js',
			[ 'jquery' ],
			'1.0.0',
			true
		);
	}

	/**
	 * Register CMB2 fields for font management.
	 *
	 * @return void
	 */
	public function register_options() {
		// since this feature is potentially dangerous, only allow super-admins to utilise it
		if ( ! current_user_can( 'manage_network_options' ) ) {
			return;
		}

		$options = new_cmb2_box(
			[
				'id'           => 'amnesty_font_options_page',
				/* translators: [admin] */
				'title'        => __( 'Fonts', 'amnesty' ),
				/* translators: [admin] */
				'tab_title'    => __( 'Fonts', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => static::$option,
				'parent_slug'  => static::$parent,
				'tab_group'    => static::$group,
				'display_cb'   => static::$display,
			] 
		);

		$options->add_field(
			[
				'id'      => 'font_load_type',
				'type'    => 'radio_inline',
				/* translators: [admin] */
				'name'    => __( 'Font Declaration Type', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Do you wish to use the default theme fonts (Amnesty Trade Gothic, Amnesty Trade Gothic Condensed), or use Google Fonts?', 'amnesty' ),
				'default' => 'theme',
				'options' => [
					/* translators: [admin] */
					'theme'  => __( 'Theme Default', 'amnesty' ),
					/* translators: [admin] */
					'remote' => __( 'Google Fonts', 'amnesty' ),
				],
			] 
		);

		$options->add_field(
			[
				'id'         => 'font_family_primary',
				'type'       => 'text',
				/* translators: [admin] */
				'name'       => __( 'Primary Font Family', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => __( 'The primary font family is used for body text.', 'amnesty' ),
				'attributes' => [
					'data-show-for' => 'remote',
				],
			] 
		);

		$options->add_field(
			[
				'id'         => 'font_family_secondary',
				'type'       => 'text',
				/* translators: [admin] */
				'name'       => __( 'Secondary Font Family', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => __( 'The secondary font family is used for headings.<br>If you only have one font family, declare the secondary font family to be the same as the primary.', 'amnesty' ),
				'attributes' => [
					'data-show-for' => 'remote',
				],
			] 
		);

		$options->add_field(
			[
				'id'              => 'font_face_url',
				'type'            => 'text_url',
				/* translators: [admin] */
				'name'            => __( 'Font Face URL', 'amnesty' ),
				/* translators: [admin] */
				'desc'            => __( 'Paste the Google Fonts URL from which the CSS file should be loaded.<br>This should start with https://fonts.googleapis.com/.', 'amnesty' ),
				'sanitization_cb' => [ $this, 'sanitise_font_url' ],
				'attributes'      => [
					'data-show-for' => 'remote',
				],
			] 
		);
	}

	/**
	 * Ensure that URL provided is a Google Fonts URL
	 *
	 * @param string $url the URL to sanitise
	 *
	 * @return string
	 */
	public function sanitise_font_url( $url = '' ) {
		if ( 0 !== strpos( trim( $url ), 'https://fonts.googleapis.com/' ) ) {
			return '';
		}

		return filter_var( $url, FILTER_SANITIZE_URL );
	}

}
