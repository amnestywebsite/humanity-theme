<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_wp_title' ) ) {
	/**
	 * Format WP title
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_wp_title(): void {
		$title = wp_title( '|', false, 'right' );

		if ( ! $title ) {
			/* translators: [front] */
			$title = __( 'Amnesty International', 'amnesty' );
		}

		printf(
			'<title>%s</title>',
			esc_html( trim( $title, " \t\n\r\v\0|" ) )
		);
	}
}

add_action( 'wp_head', 'amnesty_wp_title', 1 );

if ( ! function_exists( 'amnesty_favicons' ) ) {
	/**
	 * Output favicons for the theme
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_favicons(): void {
		printf(
			'<link rel="apple-touch-icon" sizes="180x180" href="%s">',
			esc_url( sprintf( '%s/assets/favicons/apple-touch-icon.png', get_template_directory_uri() ) )
		);

		printf(
			'<link rel="apple-touch-icon" sizes="180x180" href="%s">',
			esc_url( sprintf( '%s/assets/favicons/apple-touch-icon.png', get_template_directory_uri() ) )
		);

		printf(
			'<link rel="icon" type="image/png" sizes="32x32" href="%s">',
			esc_url( sprintf( '%s/assets/favicons/favicon-32x32.png', get_template_directory_uri() ) )
		);

		printf(
			'<link rel="icon" type="image/png" sizes="16x16" href="%s">',
			esc_url( sprintf( '%s/assets/favicons/favicon-16x16.png', get_template_directory_uri() ) )
		);

		printf(
			'<link rel="manifest" href="%s">',
			esc_url( sprintf( '%s/assets/favicons/manifest.json', get_template_directory_uri() ) )
		);

		printf(
			'<link rel="mask-icon" href="%s" color="#5bbad5">',
			esc_url( sprintf( '%s/assets/favicons/safari-pinned-tab.svg', get_template_directory_uri() ) )
		);

		echo '<meta name="msapplication-TileColor" content="#ffc40d">';
		echo '<meta name="theme-color" content="#ffffff">';
	}
}

add_action( 'wp_head', 'amnesty_favicons', 8 );
