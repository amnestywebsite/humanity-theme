<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_add_nav_menu_shortcode_support' ) ) {
	/**
	 * Add shortcode support to nav menu items
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array $items the nav menu item list
	 *
	 * @return array
	 */
	function amnesty_add_nav_menu_shortcode_support( array $items = [] ): array {
		foreach ( $items as $index => $item ) {
			$items[ $index ]->title = do_shortcode( $item->title );
		}

		return $items;
	}
}

add_filter( 'wp_nav_menu_objects', 'amnesty_add_nav_menu_shortcode_support', 10 );

if ( ! function_exists( 'amnesty_add_cart_class_to_menu_items' ) ) {
	/**
	 * Add menu item class name for cart page
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array $items the nav menu item list
	 *
	 * @return array
	 */
	function amnesty_add_cart_class_to_menu_items( array $items = [] ): array {
		if ( ! function_exists( 'wc_get_page_id' ) ) {
			return $items;
		}

		$cart_id = absint( wc_get_page_id( 'cart' ) );

		foreach ( $items as $index => $item ) {
			if ( absint( $item->object_id ) !== $cart_id ) {
				continue;
			}

			$items[ $index ]->classes[] = 'menu-item-cart';
		}

		return $items;
	}
}

add_filter( 'wp_nav_menu_objects', 'amnesty_add_cart_class_to_menu_items', 10 );
