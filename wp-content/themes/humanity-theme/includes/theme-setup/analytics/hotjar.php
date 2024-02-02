<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_output_hotjar' ) ) {
	/**
	 * Hotjar
	 *
	 * @package Amnesty\ThemeSetup\Analytics
	 *
	 * @return void
	 */
	function amnesty_output_hotjar(): void {
		$hotjar = amnesty_get_option( '_analytics_hotjar', 'amnesty_analytics_options_page' );

		if ( empty( $hotjar ) ) {
			return;
		}

		wp_enqueue_script( 'hotjar', sprintf( 'https://static.hotjar.com/c/hotjar-%s.js?sv=6', esc_attr( $hotjar ) ), [], '1.0.0', false );
		wp_localize_script(
			'hotjar',
			'_hjSettings',
			[
				'hjid' => esc_attr( $hotjar ),
				'hjsv' => 6,
			] 
		);
	}
}

add_action( 'wp_head', 'amnesty_output_hotjar', 2 );
