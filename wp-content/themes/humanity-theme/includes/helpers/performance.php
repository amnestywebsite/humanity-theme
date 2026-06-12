<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_raw_blog_option' ) ) {
	/**
	 * Retrieve an option value from a site's database
	 *
	 * Used instead of get_blog_option when filters & switching blog context
	 * need to be skipped.
	 *
	 * @param int    $blog ID of the site to retrieve the option from
	 * @param string $key  the option name
	 *
	 * @return mixed
	 */
	function amnesty_get_raw_blog_option( int $blog, string $key ): mixed {
		global $wpdb;

		$cache_key = sprintf( '%s:%s', $blog, $key );
		$cached    = wp_cache_get( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$table = $wpdb->base_prefix;
		if ( BLOG_ID_CURRENT_SITE === $blog ) {
			$table .= 'options';
		} else {
			$table .= $blog . '_options';
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- have to use a direct query to skip filters
		$option = $wpdb->get_row(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name has to be interpolated
				"SELECT * from {$table} where option_name = %s limit 1",
				[ $key ],
			),
		);

		$value = $option?->option_value ?? null;

		wp_cache_add( $cache_key, $value );

		return $value;
	}
}

if ( ! function_exists( 'amnesty_get_raw_blog_post' ) ) {
	/**
	 * Retrieve a post from a site's databse
	 *
	 * Used instead of get_blog_post when filters & switching blog context
	 * need to be skipped.
	 *
	 * @param int $blog ID of the site to retrieve the post from
	 * @param int $post ID of the post to retrieve
	 *
	 * @return WP_Post|null
	 */
	function amnesty_get_raw_blog_post( int $blog, int $post ): ?WP_Post {
		global $wpdb;

		$cache_key = sprintf( '%s:%s', $blog, $post );
		$cached    = wp_cache_get( $cache_key );

		if ( is_object( $cached ) ) {
			return new WP_Post( $cached );
		}

		$table = $wpdb->base_prefix;
		if ( BLOG_ID_CURRENT_SITE === $blog ) {
			$table .= 'posts';
		} else {
			$table .= $blog . '_posts';
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- have to use a direct query to skip filters
		$item = $wpdb->get_row(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table name has to be interpolated
				"SELECT * from {$table} where id = %d limit 1",
				[ $post ],
			),
			OBJECT,
		);

		wp_cache_add( $cache_key, $item );

		if ( ! is_array( $item ) ) {
			return null;
		}

		return new WP_Post( $item );
	}
}
