<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_woocommerce_currency' ) ) {
	/**
	 * Retrieve currency data similarly to how WC
	 * localises their scripts.
	 *
	 * {@see \Automattic\WooCommerce\Blocks\Assets::get_wc_settings_data()}
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @return array
	 */
	function amnesty_woocommerce_currency() {
		if ( ! function_exists( 'get_woocommerce_currency' ) ) {
			return false;
		}

		$data = wp_cache_get( __FUNCTION__ );

		if ( $data ) {
			return $data;
		}

		$code = get_woocommerce_currency();
		$data = [
			'code'               => $code,
			'precision'          => wc_get_price_decimals(),
			'symbol'             => html_entity_decode( get_woocommerce_currency_symbol( $code ) ),
			'position'           => get_option( 'woocommerce_currency_pos' ),
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'price_format'       => html_entity_decode( get_woocommerce_price_format() ),
		];

		wp_cache_add( __FUNCTION__, $data );

		return $data;
	}
}

if ( ! function_exists( 'amnesty_wc_number_format' ) ) {
	/**
	 * Format a number as a locale-aware price
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param mixed $number the number to format
	 *
	 * @return string the formatted number
	 */
	function amnesty_wc_price_format( $number = 0 ) {
		$number   = floatval( $number );
		$currency = amnesty_woocommerce_currency();

		if ( ! $currency ) {
			return $number;
		}

		$number = number_format(
			floatval( $number ),
			$currency['precision'],
			$currency['decimal_separator'],
			$currency['thousand_separator']
		);

		return sprintf( $currency['price_format'], $currency['symbol'], $number );
	}
}
