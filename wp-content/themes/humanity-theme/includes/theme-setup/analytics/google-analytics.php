<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_output_ga' ) ) {
	/**
	 * Google Analytics
	 *
	 * @package Amnesty\ThemeSetup\Analytics
	 *
	 * @return void
	 */
	function amnesty_output_ga(): void {
		if ( is_admin() ) {
			return;
		}

		$ga = amnesty_get_option( '_analytics_code', 'amnesty_analytics_options_page' );

		if ( empty( $ga ) ) {
			return;
		}

		wp_enqueue_script( 'ga', sprintf( 'https://www.googletagmanager.com/gtag/js?id=%s', esc_attr( $ga ) ), [], '1.0.0', false );
		wp_add_inline_script( 'ga', "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','{$ga}');" );

		$ga4 = amnesty_get_option( '_analytics_4_code', 'amnesty_analytics_options_page' );

		if ( empty( $ga4 ) ) {
			return;
		}

		wp_add_inline_script( 'ga', "gtag('config','{$ga4}');" );
	}
}

add_action( 'init', 'amnesty_output_ga' );
