<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options' ) ) {
	/**
	 * Register the theme options pages.
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @return void
	 */
	function amnesty_register_theme_options() {
		$main_options = new_cmb2_box(
			[
				'id'           => 'amnesty_theme_options_page',
				/* translators: [admin] */
				'title'        => __( 'Theme Options', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'option_key'   => 'amnesty_theme_options_page',
				'tab_group'    => 'amnesty_theme_options',
				/* translators: [admin] */
				'tab_title'    => __( 'Theme Options', 'amnesty' ),
				'display_cb'   => 'amnesty_options_display_with_tabs',
			] 
		);

		do_action( 'amnesty_register_theme_options', $main_options );
	}
}

add_action( 'cmb2_admin_init', 'amnesty_register_theme_options' );
