<?php

declare( strict_types = 1 );

add_filter( 'posts_where', 'amnesty_add_name_like_support', 10, 2 );
add_filter( 'posts_where', fn ( string $where ) => str_replace( "LIKE 'none/%'", "= ''", $where ), 100 );

if ( ! function_exists( 'amnesty_add_name_like_support' ) ) {
	/**
	 * Add support for `post_name__like` WP_Query property
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param string    $where the existing query WHERE clause(s)
	 * @param \WP_Query $query the current query object
	 *
	 * @return string
	 */
	function amnesty_add_name_like_support( string $where, WP_Query $query ): string {
		if ( ! $query->get( 'post_name__like' ) ) {
			return $where;
		}

		global $wpdb;

		return $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_name LIKE %s", $query->get( 'post_name__like' ) );
	}
}
