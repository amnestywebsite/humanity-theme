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
