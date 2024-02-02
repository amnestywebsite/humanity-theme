<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_wc_order_button_html' ) ) {
	/**
	 * Replace WooCommerce order button classes
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $html the original HTML
	 *
	 * @return string
	 */
	function amnesty_wc_order_button_html( string $html = '' ): string {
		return str_replace( 'class="button alt"', 'class="btn btn--white"', $html );
	}
}

add_filter( 'woocommerce_order_button_html', 'amnesty_wc_order_button_html' );

if ( ! function_exists( 'amnesty_dequeue_select2' ) ) {
	/**
	 * Dequeue select2, which is loaded by WooCommerce
	 *
	 * @package Amnesty
	 *
	 * @return void
	 */
	function amnesty_dequeue_select2(): void {
		if ( ! current_theme_supports( 'woocommerce' ) ) {
			return;
		}

		if ( defined( 'WOOCCM_PLUGIN_FILE' ) ) {
			wp_deregister_script( 'wooccm-checkout' );
			wp_register_script( 'wooccm-checkout', plugins_url( 'assets/frontend/js/wooccm-checkout.js', WOOCCM_PLUGIN_FILE ), [ 'jquery' ], WOOCCM_PLUGIN_VERSION, true );
		}

		if ( ! is_checkout() ) {
			wp_dequeue_style( 'select2' );
			wp_dequeue_script( 'selectWoo' );
			wp_deregister_style( 'select2' );
			wp_deregister_script( 'selectWoo' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'amnesty_dequeue_select2', 100 );
