<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_maybe_filter_quantity_input_template' ) ) {
	/**
	 * Return the "correct" quantity input template on per-page basis
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $template      the original template
	 * @param string $template_name the original template name
	 *
	 * @return string
	 */
	function amnesty_maybe_filter_quantity_input_template( string $template, string $template_name ): string {
		if ( 'global/quantity-input.php' !== $template_name ) {
			return $template;
		}

		// return woo's
		if ( get_queried_object_id() === wc_get_page_id( 'cart' ) ) {
			return sprintf( '%s/templates/%s', WC()->plugin_path(), $template_name );
		}

		global $product;

		if ( $product && 'simple' === $product->get_type() ) {
			return sprintf( '%s/templates/%s', WC()->plugin_path(), $template_name );
		}

		// return mine
		return $template;
	}
}

add_filter( 'wc_get_template', 'amnesty_maybe_filter_quantity_input_template', 10, 2 );
