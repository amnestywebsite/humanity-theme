<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_modify_order_received_title' ) ) {
	/**
	 * Change order recieved page title
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $title the original title
	 * @param mixed  $id    the object id
	 *
	 * @return string
	 */
	function amnesty_modify_order_received_title( string $title, $id = 0 ): string {
		if ( ! function_exists( 'is_order_received_page' ) ) {
			return $title;
		}

		if ( ! is_order_received_page() || absint( $id ) !== absint( get_the_ID() ) ) {
			return $title;
		}

		/* translators: [front] https://isaidotorgstg.wpengine.com/en/donate/ */
		return __( 'Thank You!', 'amnesty' );
	}
}

add_filter( 'the_title', 'amnesty_modify_order_received_title', 10, 2 );

if ( ! function_exists( 'amnesty_filter_payment_gateways' ) ) {
	/**
	 * Only show supported gateways.
	 * In this case:
	 * - hide gateways not supporting subscriptions for subscription purchases
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array $gateways the payment gateway list
	 *
	 * @return array
	 */
	function amnesty_filter_payment_gateways( array $gateways = [] ): array {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return $gateways;
		}

		if ( ! function_exists( 'amnesty_product_is_subscription' ) ) {
			return $gateways;
		}

		if ( is_admin() || ! is_checkout() ) {
			return $gateways;
		}

		$cart_items   = WC()->cart->get_cart_contents();
		$subscription = false;

		foreach ( $cart_items as $key => $value ) {
			if ( ! amnesty_product_is_subscription( $value['product_id'] ) ) {
				continue;
			}

			$subscription = true;
		}

		if ( ! $subscription ) {
			return $gateways;
		}

		foreach ( $gateways as $name => $gateway ) {
			if ( in_array( 'subscriptions', $gateway->supports, true ) ) {
				continue;
			}

			unset( $gateways[ $name ] );
		}

		return $gateways;
	}
}

add_filter( 'woocommerce_available_payment_gateways', 'amnesty_filter_payment_gateways' );
