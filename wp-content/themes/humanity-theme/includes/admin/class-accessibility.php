<?php

declare( strict_types = 1 );

namespace Amnesty;

new Accessibility();

/**
 * Register accessibility options
 *
 * @package Amnesty\Admin\Options
 */
class Accessibility {

	/**
	 * Options page parent slug
	 *
	 * @var string
	 */
	protected static $parent = 'amnesty_theme_options_page';

	/**
	 * Options group
	 *
	 * @var string
	 */
	protected static $group = 'amnesty_theme_options';

	/**
	 * Display callback
	 *
	 * @var string
	 */
	protected static $display = 'amnesty_options_display_with_tabs';

	/**
	 * Option key
	 *
	 * @var string
	 */
	protected static $option = 'amnesty_a11y';

	/**
	 * Add hooks
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front' ], 30 );
		add_action( 'cmb2_admin_init', [ $this, 'register_options' ] );
	}

	/**
	 * Generate front-end assets
	 *
	 * @return void
	 */
	public function enqueue_front() {
		$settings = get_option( static::$option );

		if ( ! empty( $settings['negishim'] ) && 'on' === $settings['negishim'] ) {
			if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			wp_enqueue_script( 'negishim', 'https://www.negishim.com/accessibility/accessibility_pro_group255.js', [ 'jquery' ], '1', true );
			wp_add_inline_script( 'negishim', sprintf( 'var accessibility_rtl=true,pixel_from_side=20,pixel_from_start=%d;', is_admin_bar_showing() ? 124 : 92 ) );
		}
	}

	/**
	 * Register CMB2 fields for font management.
	 *
	 * @return void
	 */
	public function register_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = new_cmb2_box(
			[
				'id'           => 'amnesty_a11y',
				/* translators: [admin] */
				'title'        => __( 'Accessibility', 'amnesty' ),
				/* translators: [admin] */
				'tab_title'    => __( 'Accessibility', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => static::$option,
				'parent_slug'  => static::$parent,
				'tab_group'    => static::$group,
				'display_cb'   => static::$display,
			] 
		);

		$options->add_field(
			[
				'id'   => 'negishim',
				'type' => 'checkbox',
				/* translators: [admin] */
				'name' => __( 'Negishim', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Load the Negishim Accessibility widget (Hebrew).', 'amnesty' ),
			] 
		);
	}

}
