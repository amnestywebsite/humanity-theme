<?php

declare( strict_types = 1 );

if ( ! function_exists( 'add_menu_separator' ) ) {
	/**
	 * Add an admin menu separator
	 *
	 * Does not allow overriding positions that are already in use
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @param int $position the position at which to insert the separator
	 *
	 * @return void
	 */
	function add_menu_separator( int $position ): void {
		global $menu;

		if ( ! isset( $menu[ $position ] ) ) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$menu[ $position ] = [ '', 'read', 'separator' . $position, '', 'wp-menu-separator' ];
			return;
		}

		add_menu_separator( $position + 1 );
	}
}

if ( ! function_exists( 'amnesty_reorganise_admin_menu' ) ) {
	/**
	 * Reorganise the admin menu
	 */
	function amnesty_reorganise_admin_menu(): void {
		if ( is_network_admin() ) {
			return;
		}

		global $menu;

		// move media menu item
		add_menu_separator( 30 );
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$menu[31] = $menu[10];
		add_menu_separator( 32 );

		// remove comments, original media item
		unset( $menu[25], $menu[10] );

		add_menu_separator( 40 );
	}
}

// reorganise the admin menu a bit
add_action( 'admin_menu', 'amnesty_reorganise_admin_menu', 1000 );
