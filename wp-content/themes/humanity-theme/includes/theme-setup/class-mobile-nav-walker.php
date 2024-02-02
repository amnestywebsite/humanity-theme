<?php

declare( strict_types = 1 );

namespace Amnesty;

use stdClass;
use Walker_Nav_Menu;

/**
 * Class Mobile_Nav_Walker extends Walker_Nav_Menu to add buttons to elements with child elements.
 *
 * @package Amnesty\ThemeSetup
 */
class Mobile_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		parent::start_lvl( $output, $depth, $args );

		// add the list item in as the first item in the sub list.
		// see https://github.com/amnestywebsite/amnesty-wp-theme/issues/2059#issuecomment-1334074413
		preg_match( '/.*(<li.*?>.*?)<ul class="sub-menu">/s', $output, $matches );

		if ( isset( $matches[1] ) ) {
			$parent = str_replace( 'menu-item-has-children ', '', $matches[1] );
			$parent = preg_replace(
				'/<li(.*?)>.*?data-href="([^"]*?)".*<span>(.*?)<\/span>.*/s',
				'<li$1><a href="$2"><span>$3</span></a></li>',
				$parent,
			);

			$output .= $parent;
		}
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param \WP_Post $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param mixed    $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 *
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		if ( true === apply_filters( 'amnesty_mobile_nav_menu_item_skip', false, $item, $depth, $args ) ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
		} else {
			$t = "\t";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $has_children && in_array( 'current-menu-ancestor', $classes, true ) ) {
			$classes[] = 'is-open';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param \stdClass $args  An object of wp_nav_menu() arguments.
		 * @param \WP_Post  $item  Menu item data object.
		 * @param int       $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = $this->get_item_class_attr( $classes, $item, $args, $depth );

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string    $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param \WP_Post  $item    The current menu item.
		 * @param \stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int       $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="mobile-' . esc_attr( $id ) . '"' : '';

		$list_attributes = $this->build_item_attributes( $args, $item, $depth );

		$output .= $indent . '<li' . $id . $class_names . $list_attributes . '>';

		$link_attributes = $this->build_link_attributes( $args, $item, $depth );

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string    $title The menu item's title.
		 * @param \WP_Post  $item  The current menu item.
		 * @param \stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int       $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		if ( ! is_object( $args ) ) {
			$args = new stdClass();
		}

		foreach ( [ 'before', 'link_before', 'link_after', 'after' ] as $reqd ) {
			if ( ! isset( $args->{$reqd} ) ) {
				$args->{$reqd} = '';
			}
		}

		$item_output = $this->build_item( $args, $link_attributes, $title, $has_children );

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string    $item_output The menu item's starting HTML output.
		 * @param \WP_Post  $item        Menu item data object.
		 * @param int       $depth       Depth of menu item. Used for padding.
		 * @param \stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Build the class attribute for the list item
	 *
	 * @param array    $class_list raw list of classes
	 * @param WP_Post  $item       the current item
	 * @param stdClass $args       wp_nav_menu() args
	 * @param int      $depth      depth of menu item
	 *
	 * @return string
	 */
	protected function get_item_class_attr( array $class_list, $item, $args, $depth ): string {
		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $class_list ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		return $class_names;
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 * @since 5.9.0 Renamed `$item` to `$data_object` to match parent class for PHP 8 named parameter support.
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output      Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object Menu item data object.
	 * @param int      $depth       Depth of page.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( true === apply_filters( 'amnesty_mobile_nav_menu_item_skip', false, $data_object, $depth, $args ) ) {
			return;
		}

		return parent::end_el( $output, $data_object, $depth, $args );
	}

	/**
	 * Build an item's HTML attribute list
	 *
	 * @param object $args  wp_nav_menu args
	 * @param object $item  the item object
	 * @param int    $depth the nesting depth
	 *
	 * @return string
	 */
	protected function build_item_attributes( $args, $item, $depth ) {
		$atts = apply_filters( 'amnesty_mobile_nav_menu_item_attributes', [], $item, $args, $depth );

		$attributes = '';

		foreach ( (array) $atts as $key => $value ) {
			$attributes .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return $attributes;
	}

	/**
	 * Build an item's HTML attribute list
	 *
	 * @param object $args  wp_nav_menu args
	 * @param object $item  the item object
	 * @param int    $depth the nesting depth
	 *
	 * @return string
	 */
	protected function build_link_attributes( $args, $item, $depth ) {
		$atts = [];

		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param \WP_Post  $item  The current menu item.
		 * @param \stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int       $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$atts = apply_filters( 'amnesty_mobile_nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		return $attributes;
	}

	/**
	 * Build an item's output
	 *
	 * @param object $args         object of wp_nav_menu args
	 * @param string $attributes   list of item's attributes
	 * @param string $title        the item's title
	 * @param bool   $has_children whether the item has child items
	 *
	 * @return string
	 */
	protected function build_item( $args, $attributes, $title, $has_children ) {
		if ( ! $has_children ) {
			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			return $item_output;
		}

		/* translators: %s the button title */
		$item_label = sprintf( esc_html__( 'Expand %s list', 'amnesty' ), $title );

		$item_output  = $args->before;
		$item_output .= '<button' . $attributes . ' aria-label="' . esc_attr( $item_label ) . '">';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '<i class="icon icon-arrow-down"></i>';
		$item_output .= '</button>';
		$item_output .= $args->after;

		$item_output = str_replace( ' href=', ' href="#" data-href=', $item_output );

		return $item_output;
	}

}
