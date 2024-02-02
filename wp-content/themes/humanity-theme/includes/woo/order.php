<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_wc_order_received_thank_you_text' ) ) {
	/**
	 * Change thank you page order description text
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @return string
	 */
	function amnesty_wc_order_received_thank_you_text(): string {
		/* translators: [front] https://isaidotorgstg.wpengine.com/en/donate/ */
		return __( 'Your order has been processed. Your order details are shown below for your reference:', 'amnesty' );
	}
}

add_filter( 'woocommerce_thankyou_order_received_text', 'amnesty_wc_order_received_thank_you_text' );
