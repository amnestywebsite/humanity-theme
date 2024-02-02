<?php

declare( strict_types = 1 );

if ( ! function_exists( 'get_blog_post_type' ) ) {
	/**
	 * Retrieve a post's post type in a multisite environment
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param int $blog_id the blog id to retrieve post from
	 * @param int $post_id the post to retrieve
	 *
	 * @return string
	 */
	function get_blog_post_type( $blog_id, $post_id = null ) {
		if ( ! function_exists( 'get_blog_post' ) ) {
			return '';
		}

		$post = get_blog_post( $blog_id, $post_id );

		if ( ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		return $post->post_type;
	}
}

if ( ! function_exists( 'get_blog_post_meta' ) ) {
	/**
	 * Multisite-aware get_post_meta
	 *
	 * @param int    $blog_id  the blog id to retrieve post from
	 * @param int    $post_id  the post to retrieve
	 * @param string $meta_key the meta key to retrieve
	 * @param bool   $single   whether to return a single value
	 *
	 * @return mixed
	 */
	function get_blog_post_meta( int $blog_id, int $post_id, string $meta_key = '', bool $single = false ): mixed {
		$should_switch = get_current_blog_id() !== $blog_id;

		if ( $should_switch ) {
			switch_to_blog( $blog_id );
		}

		$meta = get_post_meta( $post_id, $meta_key, $single );

		if ( $should_switch ) {
			restore_current_blog();
		}

		return $meta;
	}
}

if ( ! function_exists( 'amnesty_post_type_is_enabled' ) ) {
	/**
	 * Check whether a post type is enabled via network admin
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param string $post_type     the post_type name
	 * @param mixed  $default_value  a default value
	 *
	 * @return bool
	 */
	function amnesty_post_type_is_enabled( string $post_type = '', $default_value = null ) {
		if ( ! is_multisite() ) {
			return true;
		}

		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_post_types'][0][ $post_type ] ) ) {
			return $default_value ?: false;
		}

		return amnesty_validate_boolish( $options['enabled_post_types'][0][ $post_type ] );
	}
}

if ( ! function_exists( 'amnesty_taxonomy_is_enabled' ) ) {
	/**
	 * Check whether a taxonomy is enabled via network admin
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param string $taxonomy      the taxonomy name
	 * @param mixed  $default_value a default value
	 *
	 * @return bool
	 */
	function amnesty_taxonomy_is_enabled( string $taxonomy = '', $default_value = null ) {
		if ( ! is_multisite() ) {
			return true;
		}

		$taxonomy_object = get_taxonomy( $taxonomy );

		if ( ! is_a( $taxonomy_object, WP_Taxonomy::class ) ) {
			return false;
		}

		// if it's built-in, it's not one of our custom taxonomies
		if ( $taxonomy_object->_builtin ) {
			return true;
		}

		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_taxonomies'][0][ $taxonomy ] ) ) {
			return $default_value ?: false;
		}

		return amnesty_validate_boolish( $options['enabled_taxonomies'][0][ $taxonomy ] );
	}
}

if ( ! function_exists( 'amnesty_feature_is_enabled' ) ) {
	/**
	 * Check whether a feature is enabled via network admin
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param string $feature       the feature name
	 * @param mixed  $default_value a default value
	 *
	 * @return bool
	 */
	function amnesty_feature_is_enabled( string $feature = '', $default_value = null ) {
		if ( ! is_multisite() ) {
			return true;
		}

		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_features'][0][ $feature ] ) ) {
			return $default_value ?: false;
		}

		return amnesty_validate_boolish( $options['enabled_features'][0][ $feature ] );
	}
}

if ( ! function_exists( 'amnesty_get_feature_value' ) ) {
	/**
	 * Get feature value
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param string $feature       the feature name
	 * @param mixed  $default_value a default value
	 *
	 * @return mixed
	 */
	function amnesty_get_feature_value( string $feature = '', $default_value = null ) {
		if ( ! is_multisite() ) {
			return $default_value;
		}

		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_features'][0][ $feature ] ) ) {
			return $default_value ?: false;
		}

		return $options['enabled_features'][0][ $feature ];
	}
}

if ( ! function_exists( 'network_menu_page_url' ) ) {
	/**
	 * Get the URL to access a particular network menu page based on the slug it was registered with.
	 *
	 * If the slug hasn't been registered properly, no URL will be returned.
	 *
	 * @see menu_page_url()
	 *
	 * @package Amnesty\Multisite
	 *
	 * @global array $_parent_pages
	 *
	 * @param string $menu_slug The slug name to refer to this menu by (should be unique for this menu).
	 * @param bool   $output    Whether or not to echo the URL. Default true.
	 *
	 * @return string The menu page URL.
	 */
	function network_menu_page_url( $menu_slug, $output = true ) {
		global $_parent_pages;

		if ( ! isset( $_parent_pages[ $menu_slug ] ) ) {
			if ( $output ) {
				echo '';
			}

			return '';
		}

		$parent_slug = $_parent_pages[ $menu_slug ];

		if ( $parent_slug && ! isset( $_parent_pages[ $parent_slug ] ) ) {
			$url = network_admin_url( add_query_arg( 'page', $menu_slug, $parent_slug ) );
		} else {
			$url = network_admin_url( 'admin.php?page=' . $menu_slug );
		}

		if ( $output ) {
			echo esc_url( $url );
		}

		return $url;
	}
}

if ( ! function_exists( 'amnesty_get_sites' ) ) {
	/**
	 * Retrieve info for all sites on network
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param bool $filter whether to apply filters
	 *
	 * @return array
	 */
	function amnesty_get_sites( bool $filter = true ) {
		if ( ! defined( 'MULTISITE' ) || ! MULTISITE ) {
			return [];
		}

		$cached = wp_cache_get( 'amnesty_get_sites' );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$sites = ( new \Amnesty\Core_Site_List() )->get_sites();
		wp_cache_set( 'amnesty_get_sites', $sites );

		if ( ! $filter ) {
			return $sites;
		}

		return apply_filters( 'amnesty_get_sites', $sites );
	}
}

if ( ! function_exists( 'get_blog_term_link' ) ) {
	/**
	 * Equivalent to {@see get_term_link}, with blog switching built-in
	 *
	 * @package Amnesty\Multisite
	 *
	 * @param int    $blog_id  the target site ID
	 * @param int    $term_id  the target term ID
	 * @param string $taxonomy the target term taxonomy
	 *
	 * @return string
	 */
	function get_blog_term_link( int $blog_id, int $term_id, string $taxonomy = '' ): string {
		switch_to_blog( $blog_id );
		$link = get_term_link( $term_id, $taxonomy );
		restore_current_blog();

		return is_wp_error( $link ) ? home_url( '/', 'https' ) : $link;
	}
}
