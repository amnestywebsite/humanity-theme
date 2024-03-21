<?php

declare( strict_types = 1 );

add_filter( 'amnesty_get_sites', 'amnesty_get_object_translations' );

if ( ! function_exists( 'amnesty_get_object_translations' ) ) {
	/**
	 * Overload the site list using MLP, if available
	 *
	 * @see amnesty_get_sites()
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param array $sites the original site list
	 *
	 * @return array<string,object>
	 */
	function amnesty_get_object_translations( array $sites = [] ) {
		if ( ! has_translations() || is_admin() ) {
			return $sites;
		}

		/** Global WP object @var \WP $wp */
		global $wp;

		// skip if current route is download
		if ( $wp->query_vars['download'] ?? false ) {
			return $sites;
		}

		$translations = get_object_translations();
		$i18n_sites   = [];

		foreach ( $translations as $translation ) {
			$language = $translation->language();

			if ( ! $translation->remoteContentId() ) {
				continue;
			}

			// if post isn't published, skip it
			if ( 'post' === $translation->type() ) {
				$post_object  = get_blog_post( $translation->remoteSiteId(), $translation->remoteContentId() );
				$is_published = is_a( $post_object, '\WP_Post' ) && 0 !== absint( $post_object->ID ) &&
					'attachment' !== $post_object->post_type && 'publish' === $post_object->post_status;

				if ( ! $is_published ) {
					continue;
				}
			}

			$lang = get_site_language_name( $translation->remoteSiteId() );
			if ( ! $lang ) {
				$lang = strip_language_name_parentheticals( $language->name() );
			}

			$i18n_sites[] = (object) [
				'lang'      => $lang,
				'code'      => $language->isoCode(),
				'direction' => $language->isRtl() ? 'rtl' : 'ltr',
				'name'      => get_blog_option( $translation->remoteSiteId(), 'blogname' ),
				'url'       => $translation->remoteUrl(),
				'blog_id'   => $translation->remoteSiteId(),
				'item_id'   => $translation->remoteContentId(),
				'type'      => $translation->type(),
				'current'   => $translation->remoteSiteId() === get_current_blog_id(),
			];
		}

		return $i18n_sites;
	}
}

if ( ! function_exists( 'render_language_selector_dropdown' ) ) {
	/**
	 * Render the language selector as a dropdown within the full width (read: dotorg) nav
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return void
	 */
	function render_language_selector_dropdown() {
		if ( ! is_multilingualpress_enabled() ) {
			return;
		}

		$return_early = apply_filters( 'render_language_selector_dropdown_return_early', false );

		if ( true === $return_early ) {
			return;
		}

		$sites = amnesty_get_object_translations();

		// fall back to home page if no translations
		if ( ! count( $sites ) ) {
			foreach ( amnesty_get_sites() as &$site ) {
				$site->item_id = absint( get_blog_option( $site->site_id, 'page_on_front', '0' ) );
				$site->blog_id = $site->site_id;
				$site->type    = 'post';

				$sites[] = $site;
			}
		}

		$others  = array_filter( $sites, fn ( object $site ): bool => get_current_blog_id() !== $site->blog_id );
		$current = array_filter( $sites, fn ( object $site ): bool => get_current_blog_id() === $site->blog_id );
		$current = array_values( $current );

		if ( ! count( $current ) ) {
			return;
		}

		$wrapper_open  = sprintf( '<nav class="page-nav page-nav--left" aria-label="%s" aria-expanded="false"><ul class="site-selector">', /* translators: [front] */ esc_attr__( 'Available languages', 'amnesty' ) );
		$wrapper_close = '</ul></nav><div class="site-separator"></div>';

		$current_item_name = get_blog_option( $current[0]->blog_id, 'amnesty_language_name' ) ?: $current[0]->lang;
		$current_item_open = sprintf(
			'<li id="menu-item-%2$s-%3$s" class="menu-item menu-item-current menu-item-has-children menu-item-%2$s-%3$s" dir="%4$s"><span>%1$s</span>',
			esc_html( ucwords( $current_item_name, 'UTF-8' ) ),
			esc_attr( $current[0]->blog_id ),
			esc_attr( $current[0]->item_id ),
			esc_attr( $current[0]->direction ),
		);

		$current_item_close = '</li>';
		$other_items_open   = '<ul class="sub-menu">';
		$other_items_inner  = '';
		$other_items_close  = '</ul>';

		foreach ( amnesty_get_sites() as $site ) {
			if ( $site->current ) {
				continue;
			}

			$other_items_inner .= render_language_selector_dropdown_item( $site, $others );
		}

		$html = implode(
			'',
			[
				$wrapper_open,
				$current_item_open,
				$other_items_open,
				$other_items_inner,
				$other_items_close,
				$current_item_close,
				$wrapper_close,
			]
		);

		echo wp_kses_post( $html );
	}
}

if ( ! function_exists( 'render_language_selector_dropdown_item' ) ) {
	/**
	 * Render an item in the language selector dropdown
	 *
	 * For non-current items only.
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 * @see render_language_selector_dropdown()
	 *
	 * @param object $site  the site object
	 * @param array  $sites the list of sites
	 *
	 * @return string
	 */
	function render_language_selector_dropdown_item( object $site, array $sites ): string {
		$found = array_filter( $sites, fn ( object $remote ): bool => $remote->blog_id === $site->site_id );
		$found = array_values( $found );

		// fallback to homepage if no translation
		if ( ! count( $found ) ) {
			$name = get_blog_option( $site->site_id, 'ammesty_language_name' ) ?: $site->lang;

			return sprintf(
				'<li id="menu-item-%3$s-%4$s" class="menu-item menu-item-%3$s-%4$s" dir="%5$s"><a href="%2$s">%1$s</a></li>',
				esc_html( $name ),
				esc_url( $site->url ),
				esc_attr( $site->site_id ),
				esc_attr( $site->post_id ),
				esc_attr( $site->direction ),
			);
		}

		$item = $found[0];
		$link = 'post' === $item->type ? get_blog_permalink( $item->blog_id, $item->item_id ) : get_blog_term_link( $item->blog_id, $item->item_id );
		$name = get_blog_option( $item->blog_id, 'ammesty_language_name' ) ?: $item->lang;

		return sprintf(
			'<li id="menu-item-%3$s-%4$s" class="menu-item menu-item-%3$s-%4$s" dir="%5$s"><a href="%2$s">%1$s</a></li>',
			esc_html( $name ),
			esc_url( $link ),
			esc_attr( $item->blog_id ),
			esc_attr( $item->item_id ),
			esc_attr( get_string_textdirection( $item->post_title ?? $item->name ) ),
		);
	}
}

if ( ! function_exists( 'amnesty_wp_get_nav_menu_items' ) ) {
	/**
	 * Replace WordPress' menu objects with our own, to allow language switching
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param array<int,stdClass> $items the existing items collection
	 * @param stdClass            $menu  the menu object
	 *
	 * @return array
	 */
	function amnesty_wp_get_nav_menu_items( $items, $menu ) {
		if ( 'site-selector' !== $menu->slug || is_admin() ) {
			return $items;
		}

		$sites   = amnesty_get_object_translations();
		$current = array_values( array_filter( $sites, fn ( $site ) => amnesty_validate_boolish( $site->current ) ) );
		$others  = array_values( array_filter( $sites, fn ( $site ) => ! amnesty_validate_boolish( $site->current ) ) );

		$new_items = [];

		if ( ! empty( $current[0] ) ) {
			$new_items[] = (object) [
				'ID'               => $current[0]->post_id,
				'post_title'       => $current[0]->lang,
				'menu_item_parent' => 0,
				'object_id'        => $current[0]->post_id,
				'object'           => 'custom',
				'type'             => 'custom',
				'type_label'       => 'Custom Link',
				'title'            => $current[0]->lang,
				'url'              => $current[0]->url,
				'classes'          => [],
			];
		}

		foreach ( $others as $other ) {
			$new_items[] = (object) [
				'ID'               => $other->post_id,
				'post_title'       => $other->lang,
				'menu_item_parent' => $current[0]->post_id ?? 0,
				'object_id'        => $other->post_id,
				'object'           => 'custom',
				'type'             => 'custom',
				'type_label'       => 'Custom Link',
				'title'            => $other->lang,
				'url'              => $other->url,
				'classes'          => [],
			];
		}

		return $new_items;
	}
}

/**
 * Replace WordPress' menu objects with our own to allow language switching
 */
add_filter( 'wp_get_nav_menu_items', 'amnesty_wp_get_nav_menu_items', 100, 2 );
