<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_header_object_id' ) ) {
	/**
	 * Retrieve the ID of the current object (in the context of the header)
	 *
	 * @package Amnesty
	 *
	 * @return int
	 */
	function amnesty_get_header_object_id(): int {
		$object_id = get_the_ID();

		if ( is_home() ) {
			$object_id = get_option( 'page_for_posts' );
		}

		if ( is_front_page() ) {
			$object_id = get_option( 'page_on_front' );
		}

		if ( is_archive() ) {
			$object_id = get_option( 'page_for_posts' );
		}

		return absint( $object_id );
	}
}

if ( ! function_exists( 'amnesty_get_header_style' ) ) {
	/**
	 * Retrieve the correct header style name
	 *
	 * @package Amnesty
	 *
	 * @param int $object_id the current object ID
	 *
	 * @return string
	 */
	function amnesty_get_header_style( int $object_id ): string {
		$default_style = 'light';
		$config_style  = amnesty_get_option( '_header_style' ) ?: $default_style;

		if ( strpos( $config_style, 'transparent' ) ) {
			$config_style = 'transparent-light';
		} else {
			$config_style = $default_style;
		}

		$override_style = amnesty_get_meta_field( '_nav_style', $object_id );

		if ( ! $override_style || 'global' === $override_style ) {
			return $config_style;
		}

		return $override_style;
	}
}

if ( ! function_exists( 'count_top_level_menu_items' ) ) {
	/**
	 * Count the number of top level menu items in a menu
	 *
	 * @package Amnesty
	 *
	 * @param string $location the menu location
	 * @param string $menu     the menu name
	 *
	 * @return int
	 */
	function count_top_level_menu_items( string $location = 'main-menu', string $menu = '' ): int {
		$menu_object = $menu ? wp_get_nav_menu_object( $menu ) : wp_get_nav_menu_object( get_nav_menu_locations()[ $location ] ?? 0 );

		if ( ! $menu_object ) {
			return 0;
		}

		$menu_items = wp_get_nav_menu_items( $menu_object->term_id, [ 'update_post_term_cache' => false ] );
		$top_level  = array_filter( $menu_items, fn ( $item ) => 0 === absint( $item->menu_item_parent ) );

		return count( $top_level );
	}
}

if ( ! function_exists( 'is_on_nav_submenu_page' ) ) {
	/**
	 * Check whether the current view is in a main nav's subnav
	 *
	 * @package Amnesty
	 *
	 * @return bool
	 */
	function is_on_nav_submenu_page(): bool {
		$menu = wp_get_nav_menu_object( get_nav_menu_locations()['main-menu'] ?? 0 );

		if ( ! $menu ) {
			return false;
		}

		$menu_items  = wp_get_nav_menu_items( $menu->term_id, [ 'update_post_term_cache' => false ] );
		$child_items = array_filter( $menu_items, fn ( $item ) => 0 !== absint( $item->menu_item_parent ) );
		$current_url = current_url();

		foreach ( $child_items as $kid ) {
			if ( $kid->url === $current_url ) {
				return true;
			}
		}

		return false;
	}
}
