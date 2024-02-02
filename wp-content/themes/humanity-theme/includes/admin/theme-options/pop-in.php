<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_pop_in' ) ) {
	/**
	 * Register theme options for the Pop-in feature
	 *
	 * @package Amnesty\ThemeOptions
	 *
	 * @param CMB2 $options the options object
	 *
	 * @return void
	 */
	function amnesty_register_theme_options_pop_in( CMB2 $options ): void {
		if ( ! amnesty_feature_is_enabled( 'pop-in' ) ) {
			return;
		}

		$options = new_cmb2_box(
			[
				'id'           => 'amnesty_pop_in_options_page',
				'option_key'   => 'amnesty_pop_in_options_page',
				/* translators: [admin] */
				'title'        => __( 'Pop-in', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'tab_group'    => 'amnesty_theme_options',
				/* translators: [admin] */
				'tab_title'    => __( 'Pop-in', 'amnesty' ),
				'parent_slug'  => 'amnesty_theme_options_page',
				'display_cb'   => 'amnesty_options_display_with_tabs',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'    => __( 'Active Pop-in Call to Action', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Drag a pop-in from the left column to the right column to activate.', 'amnesty' ),
				'id'      => 'active_pop_in',
				'type'    => 'custom_attached_posts',
				'column'  => true,
				'options' => [
					'show_thumbnails' => false,
					'filter_boxes'    => false,
					'query_args'      => [
						'posts_per_page' => 10,
						'post_type'      => 'pop-in',
					],
				],
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'            => __( 'Timeout', 'amnesty' ),
				/* translators: [admin] */
				'desc'            => __( 'After how many days should a dismissed pop-in be re-shown to the user?', 'amnesty' ),
				'id'              => 'pop_in_timeout',
				'type'            => 'text',
				'default'         => '30',
				'sanitization_cb' => 'cmb2_validate_integer',
				'escape_cb'       => 'cmb2_validate_integer',
				'attributes'      => [
					'type'    => 'number',
					'pattern' => '\d+',
				],
			]
		);
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_pop_in' );
