<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_shortcode_cart_icon' ) ) {
	/**
	 * Register a cart icon shortcode
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param mixed  $args    shortcode properties
	 * @param string $content shortcode content
	 *
	 * @return string
	 */
	function amnesty_shortcode_cart_icon( $args, string $content = '' ): string {
		$label = $args['label'] ?? $content;
		$hash  = bin2hex( random_bytes( 1 ) );
		$label = sprintf( '<span id="%s" class="u-hiddenVisually">%s</span>', esc_attr( $hash ), esc_html( $label ) );
		$icon  = sprintf( '<i class="icon icon-cart" aria-labelledby="%s"></i>', esc_attr( $hash ) );

		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart->cart_contents_count ) ) {
			return $label . $icon;
		}

		return $label . $icon . sprintf(
			'<span aria-label="%s">%s</span>',
			/* translators: [front] https://isaidotorgstg.wpengine.com/en/donate/ */
			esc_html__( 'Number of items in your cart', 'amnesty' ),
			absint( WC()->cart->cart_contents_count )
		);
	}
}

add_shortcode( 'cart_icon', 'amnesty_shortcode_cart_icon' );

if ( ! function_exists( 'amnesty_nav_menu_cart_class' ) ) {
	/**
	 * Add a CSS class to a nav menu item if it contains a cart icon shortcode
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array  $classes the existing item classes
	 * @param object $item    the menu item object
	 *
	 * @return array
	 */
	function amnesty_nav_menu_cart_class( array $classes, object $item ): array {
		if ( false === strpos( $item->title, 'cart-label' ) ) {
			return $classes;
		}

		$classes[] = 'has-icon';
		return $classes;
	}
}

// add an icon to the cart_icon shortcode's menu item wrapper
add_filter( 'nav_menu_css_class', 'amnesty_nav_menu_cart_class', 10, 2 );

if ( ! function_exists( 'amnesty_add_icon_to_cart_removal_link' ) ) {
	/**
	 * Add icon to cart item removal link
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $link     the original remove link
	 * @param string $item_key the WC cart item key
	 *
	 * @return string
	 */
	function amnesty_add_icon_to_cart_removal_link( string $link = '', string $item_key = '' ): string {
		$product = WC()->cart->get_cart()[ $item_key ] ?? false;

		if ( ! $product ) {
			return $link;
		}

		$link = sprintf(
			'<a class="remove-link" href="%s" aria-label="%s" data-product_id= "%s" data-product_sku="%s">%s</a>',
			esc_url( wc_get_cart_remove_url( $item_key ) ),
			/* translators: [front] https://isaidotorgstg.wpengine.com/en/donate/ */
			esc_html__( 'Remove this item', 'woocommerce' ),
			esc_attr( $product['product_id'] ),
			esc_attr( $product['data']->get_sku() ),
			'<i class="icon-remove"></i>'
		);

		return $link;
	}
}

add_filter( 'woocommerce_cart_item_remove_link', 'amnesty_add_icon_to_cart_removal_link', 10, 2 );

if ( ! function_exists( 'amnesty_remove_button_from_flash_message' ) ) {
	/**
	 * Strip button from add-to-cart flash message
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param string $message the flash message HTML
	 *
	 * @return string
	 */
	function amnesty_remove_button_from_flash_message( string $message = '' ): string {
		return preg_replace( '/<a[^>]*>[^<]*<\/a>\s*/', '', $message );
	}
}

add_filter( 'wc_add_to_cart_message_html', 'amnesty_remove_button_from_flash_message' );
