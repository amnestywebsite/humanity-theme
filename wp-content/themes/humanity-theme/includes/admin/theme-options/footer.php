<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_footer' ) ) {
	/**
	 * Register theme options for the site footer
	 */
	function amnesty_register_theme_options_footer(): void {
		$footer = new_cmb2_box(
			[
				'id'           => 'amnesty_footer_options_page',
				'option_key'   => 'amnesty_footer_options_page',
				/* translators: [admin] */
				'title'        => __( 'Footer', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'tab_group'    => 'amnesty_theme_options',
				/* translators: [admin] */
				'tab_title'    => __( 'Footer', 'amnesty' ),
				'parent_slug'  => 'amnesty_theme_options_page',
				'display_cb'   => 'amnesty_options_display_with_tabs',
			]
		);

		$footer->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Section Title', 'amnesty' ),
				'id'   => '_footer_title',
				'type' => 'text',
			]
		);

		$footer->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Section Content', 'amnesty' ),
				'id'   => '_footer_content',
				'type' => 'wysiwyg',
			]
		);

		$footer->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Section Call to Action text', 'amnesty' ),
				'id'   => '_footer_cta_text',
				'type' => 'text',
			]
		);

		$footer->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Section Call to Action url', 'amnesty' ),
				'id'   => '_footer_cta_url',
				'type' => 'text',
			]
		);

		/* translators: [admin] */
		amnesty_cmb2_wrap_open( $footer, __( 'Copyright info', 'amnesty' ) );

		$footer->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Copyright info', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'By default, the copyright info outputs the current year followed by the site name; add content to this field to override this.', 'amnesty' ),
				'id'   => 'copyright_info',
				'type' => 'text',
			]
		);

		amnesty_cmb2_wrap_close( $footer );
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_footer' );
