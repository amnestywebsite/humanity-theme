<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_general_settings' ) ) {
	/**
	 * Register custom fields on general settings page
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @return void
	 */
	function amnesty_register_general_settings() {
		add_settings_field(
			'amnesty_language_name',
			/* translators: [admin] */
			__( 'Language Display Name', 'amnesty' ),
			'amnesty_render_setting_language_name',
			'general'
		);

		register_setting( 'general', 'amnesty_language_name', 'sanitize_text_field' );
	}
}

if ( ! function_exists( 'amnesty_render_setting_language_name' ) ) {
	/**
	 * Render the language name settings field
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @return void
	 */
	function amnesty_render_setting_language_name() {
		$value = get_option( 'amnesty_language_name' );
		printf(
			'<input id="amnesty-language-name" class="regular-text" name="amnesty_language_name" type="text" value="%1$s">',
			esc_attr( $value )
		);

		printf(
			'<p class="description">%s</p>',
			/* translators: [admin] */
			esc_html__( 'Override the site language display name on the site frontend', 'amnesty' )
		);
	}
}

add_action( 'admin_init', 'amnesty_register_general_settings' );
