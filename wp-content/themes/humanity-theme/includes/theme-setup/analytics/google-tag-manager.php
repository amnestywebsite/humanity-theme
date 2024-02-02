<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_output_gtm' ) ) {
	/**
	 * Google Tag Manager
	 *
	 * @package Amnesty\ThemeSetup\Analytics
	 *
	 * @return void
	 */
	function amnesty_output_gtm(): void {
		if ( is_admin() ) {
			return;
		}

		$gtm = amnesty_get_option( 'gtm_code', 'amnesty_analytics_options_page' );

		if ( empty( $gtm ) ) {
			return;
		}

		wp_enqueue_script( 'gtm', sprintf( 'https://www.googletagmanager.com/gtm.js?id=%s', esc_attr( $gtm ) ), [], '1.0.0', false );
		wp_add_inline_script( 'gtm', 'window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)};', 'before' );

		$consent = amnesty_get_option( 'gtm_consent_mode', 'amnesty_analytics_options_page' );

		if ( $consent && 'inactive' !== $consent ) {
			wp_add_inline_script( 'gtm', sprintf( "gtag('consent','default',{'ad_storage':'%1\$s','analytics_storage':'%1\$s'});", esc_js( $consent ) ), 'before' );
		} else {
			wp_add_inline_script( 'gtm', "gtag({'gtm.start':new Date().getTime(),event:'gtm.js'});", 'after' );
		}
	}
}

add_action( 'init', 'amnesty_output_gtm', 1 );
