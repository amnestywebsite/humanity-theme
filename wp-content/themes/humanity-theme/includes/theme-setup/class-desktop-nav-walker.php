<?php

declare( strict_types = 1 );

namespace Amnesty;

use stdClass;
use Walker_Nav_Menu;

/**
 * Class used to implement an HTML list of nav menu items.
 *
 * @package Amnesty\ThemeSetup
 */
class Desktop_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string    $output Used to append additional content (passed by reference).
	 * @param int       $depth  Depth of menu item. Used for padding.
	 * @param \stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$classes = [ 'sub-menu' ];

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array<int,string> $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param \stdClass         $args    An object of `wp_nav_menu()` arguments.
		 * @param int               $depth   Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<nav$class_names><ul class=\"sub-menu-inner\">{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string    $output Used to append additional content (passed by reference).
	 * @param int       $depth  Depth of menu item. Used for padding.
	 * @param \stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul></nav>{$n}";
	}

}
