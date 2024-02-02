<?php

// phpcs:disable PSR12.Files.FileHeader.IncorrectOrder

declare( strict_types = 1 );

/**
 * Wrap WooCommerce dropdowns with our wrapper
 */
add_filter( 'woocommerce_form_field_select', 'amnesty_wrap_select_html', 1000 );
add_filter( 'woocommerce_form_field_country', 'amnesty_wrap_select_html', 1000 );
add_filter( 'woocommerce_form_field_state', 'amnesty_wrap_select_html', 1000 );
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'amnesty_wrap_select_html' );

if ( ! function_exists( 'amnesty_wrap_select_html' ) ) {
	/**
	 * Wrap select elements in our styled wrapper
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $html the html to wrap
	 *
	 * @return string
	 */
	function amnesty_wrap_select_html( $html = '' ) {
		if ( false === strpos( $html, '<select' ) ) {
			return $html;
		}

		if ( false !== strpos( $html, 'element-select' ) ) {
			return $html;
		}

		return preg_replace(
			'/(<select[^>]*>(?!<\/select).*<\/select>)/',
			'<span class="element-select">$1</span>',
			$html
		);
	}
}
