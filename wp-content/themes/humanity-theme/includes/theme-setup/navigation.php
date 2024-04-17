<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_logo' ) ) {
	/**
	 * Output the theme logo markup
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_logo() {
		$logo_link   = amnesty_get_option( '_header_logo_link' ) ?: home_url();
		$logotype_id = amnesty_get_option( '_site_logotype_id' );
		$logomark_id = amnesty_get_option( '_site_logomark_id' );

		$logotype = '';
		$logomark = '';

		if ( $logotype_id ) {
			$logotype = wp_get_attachment_image(
				$logotype_id,
				'logotype',
				false,
				[
					'class' => 'logo-logoType',
					'alt'   => get_bloginfo( 'name' ),
				]
			);
		}

		if ( $logomark_id ) {
			$logomark = wp_get_attachment_image(
				$logomark_id,
				'logomark',
				false,
				[
					'class' => 'logo-logoMark',
					'alt'   => get_bloginfo( 'name' ),
				]
			);
		}

		printf(
			'<a class="logo" href="%s" aria-label="%s">%s</a>',
			esc_url( $logo_link ),
			esc_attr( get_bloginfo( 'name' ) ),
			$logotype . $logomark // phpcs:ignore
		);
	}
}

if ( ! function_exists( 'amnesty_nav_should_display' ) ) {
	/**
	 * Check if a nav menu should output.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param string $name the nav to check.
	 *
	 * @return bool
	 */
	function amnesty_nav_should_display( $name = '' ) {
		$locations = get_nav_menu_locations();
		if ( ! isset( $locations[ $name ] ) ) {
			return false;
		}

		$nav = wp_get_nav_menu_object( $locations[ $name ] );
		if ( false === $nav || ! is_a( $nav, 'WP_Term' ) || 0 === $nav->count ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'amnesty_nav' ) ) {
	/**
	 * Wrapper for wp_nav_mnu with our desired options
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param string $name   desired menu name to show.
	 * @param string $walker custom walker to use.
	 *
	 * @return void
	 */
	function amnesty_nav( $name = 'main-menu', $walker = '' ) {
		if ( ! amnesty_nav_should_display( $name ) ) {
			return;
		}

		if ( 'site-selection' === $name && is_multilingualpress_enabled() ) {
			/**
			 * MLP adds a filter to this with a priority of PHP_INT_MAX,
			 * and whose callback is a proxied closure, making it impossible
			 * to remove in a sensible way (it breaks things). Since we can
			 * reasonably assume that there aren't going to be any filters
			 * applied with a higher priority, we remove it by popping it off
			 * the end of the array of callbacks bound to the filter.
			 */
			array_pop( $GLOBALS['wp_filter']['wp_get_nav_menu_items']->callbacks );
		}

		wp_nav_menu(
			[
				'theme_location'  => $name,
				'menu'            => '',
				'container'       => false,
				'container_class' => 'menu-{menu slug}-container',
				'container_id'    => '',
				'menu_class'      => 'menu',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '<span>',
				'link_after'      => '</span>',
				'items_wrap'      => '%3$s',
				'depth'           => 0,
				'walker'          => $walker,
			]
		);
	}
}

if ( ! function_exists( 'amnesty_register_menu' ) ) {
	/**
	 * Register the theme menus.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_register_menu() {
		register_nav_menus(
			[
				/* translators: [admin] */
				'main-menu'         => __( 'Main Menu', 'amnesty' ),
				/* translators: [admin] */
				'site-selection'    => __( 'Site Selector', 'amnesty' ),
				/* translators: [admin] */
				'footer-navigation' => __( 'Footer Main', 'amnesty' ),
				/* translators: [admin] */
				'footer-legal'      => __( 'Footer Legal', 'amnesty' ),
			]
		);
	}

	add_action( 'init', 'amnesty_register_menu' );
}

// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $menu ) {
		if ( ! isset( $menu->menu_id ) || 'category_style_menu' !== $menu->menu_id ) {
			return $atts;
		}

		$atts['class'] = 'btn btn--white';

		return $atts;
	},
	100,
	3
);

// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $menu ) {
		if ( ! isset( $menu->menu_id ) || 'footer-navigation' !== $menu->menu_id ) {
			return $atts;
		}

		$atts['id'] = '';

		return $atts;
	},
	100,
	3
);

// add class to body to indicate that a subnav has an active item
add_filter(
	'body_class',
	function ( $class_name ) {
		if ( is_admin() || ! is_on_nav_submenu_page() ) {
			return $class_name;
		}

		$class_name[] = 'sub-nav-active';

		return $class_name;
	}
);

// add header style to body class
add_filter(
	'body_class',
	function ( $class_name ) {
		if ( is_admin() ) {
			return $class_name;
		}

		$class_name[] = 'has-' . amnesty_get_header_style( amnesty_get_header_object_id() ) . '-header';

		return $class_name;
	}
);
