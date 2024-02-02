<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_meta_field' ) ) {
	/**
	 * The same as get_post_meta but switches the arguments order round
	 * so only the meta key is required.
	 *
	 * @package Amnesty
	 *
	 * @param string   $field   Desired meta key.
	 * @param bool|int $post_id Post id.
	 * @param bool     $single  Should this return a single piece of meta data.
	 *
	 * @return mixed
	 */
	function amnesty_get_meta_field( $field, $post_id = false, $single = true ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();

			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			}

			if ( is_front_page() ) {
				$post_id = get_option( 'page_on_front' );
			}
		}

		return get_post_meta( $post_id, $field, $single );
	}
}

if ( ! function_exists( 'amnesty_get_option' ) ) {
	/**
	 * Wrapper function around cmb2_get_option.
	 *
	 * @package Amnesty
	 *
	 * @since  0.1.0
	 *
	 * @param string $key           Options array key.
	 * @param string $options       Which options page to use.
	 * @param mixed  $default_value Optional default value.
	 *
	 * @return mixed
	 */
	function amnesty_get_option( $key = '', $options = 'amnesty_theme_options_page', $default_value = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( $options, $key, $default_value );
		}

		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( $options, $default_value );
		$val  = $default_value;

		if ( 'all' === $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}
}

if ( ! function_exists( 'amnesty_copy_postmeta' ) ) {
	/**
	 * Copy an array of postmeta to a site
	 *
	 * @package Amnesty
	 *
	 * @param int                 $blog_id the target blog ID
	 * @param int                 $post_id the target post ID
	 * @param array<string,mixed> $meta    the metadata to copy
	 *
	 * @return void
	 */
	function amnesty_copy_postmeta( int $blog_id, int $post_id, array $meta ): void {
		switch_to_blog( $blog_id );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		restore_current_blog();
	}
}
