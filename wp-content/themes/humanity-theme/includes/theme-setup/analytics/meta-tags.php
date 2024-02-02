<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_output_meta_tags' ) ) {
	/**
	 * Output <meta> tags - site verification, facebook pages, etc
	 *
	 * @package Amnesty\ThemeSetup\Analytics
	 *
	 * @return void
	 */
	function amnesty_output_meta_tags(): void {
		if ( is_admin() ) {
			return;
		}

		$meta = amnesty_get_option( 'meta', 'amnesty_analytics_options_page', [] );
		$rdf  = amnesty_get_option( 'rdf', 'amnesty_analytics_options_page', [] );

		foreach ( $meta as $item ) {
			printf( '<meta name="%s" content="%s" />%s', esc_attr( $item['key'] ), esc_attr( $item['value'] ), "\n" );
		}

		foreach ( $rdf as $item ) {
			printf( '<meta property="%s" content="%s" />%s', esc_attr( $item['key'] ), esc_attr( $item['value'] ), "\n" );
		}
	}
}

add_action( 'wp_head', 'amnesty_output_meta_tags', 1 );
