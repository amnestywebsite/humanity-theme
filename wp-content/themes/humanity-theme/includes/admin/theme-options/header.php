<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_header' ) ) {
	/**
	 * Register theme options for the site header
	 *
	 * @package Amnesty\ThemeOptions
	 *
	 * @param CMB2 $options the options object
	 *
	 * @return void
	 */
	function amnesty_register_theme_options_header( CMB2 $options ): void {
		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Header', 'amnesty' ),
				'id'   => '_header_title',
				'type' => 'title',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'         => __( 'Site Logotype', 'amnesty' ),
				'id'           => '_site_logotype',
				'type'         => 'file',
				'options'      => [ 'url' => false ],
				'preview_size' => 'thumbnail',
				'query_args'   => [
					'type' => [
						'image/gif',
						'image/png',
						'image/jpeg',
						'image/svg+xml',
					],
				],
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'         => __( 'Site Logomark', 'amnesty' ),
				'id'           => '_site_logomark',
				'type'         => 'file',
				'options'      => [ 'url' => false ],
				'preview_size' => 'thumbnail',
				'query_args'   => [
					'type' => [
						'image/gif',
						'image/png',
						'image/jpeg',
						'image/svg+xml',
					],
				],
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Logo Link', 'amnesty' ),
				'id'   => '_header_logo_link',
				'type' => 'text',
			]
		);
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_header' );
