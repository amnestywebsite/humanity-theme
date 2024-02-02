<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_add_custom_columns' ) ) {
	/**
	 * Add custom columns to list tables
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @param array $columns the table's columns
	 *
	 * @return array
	 */
	function amnesty_add_custom_columns( array $columns ): array {
		$new_columns = [
			'cb' => $columns['cb'],
			'id' => __( 'ID' ),
		];

		array_shift( $columns );

		return array_merge( $new_columns, $columns );
	}
}

add_filter( 'manage_pages_columns', 'amnesty_add_custom_columns' );
add_filter( 'manage_posts_columns', 'amnesty_add_custom_columns' );

if ( ! function_exists( 'amnesty_remove_comments_column' ) ) {
	/**
	 * Remove the comments coliumn from list tables
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @param array $columns the table's columns
	 *
	 * @return array
	 */
	function amnesty_remove_comments_column( array $columns ): array {
		unset( $columns['comments'] );
		return $columns;
	}
}

add_filter( 'manage_posts_columns', 'amnesty_remove_comments_column' );

if ( ! function_exists( 'amnesty_render_custom_columns' ) ) {
	/**
	 * Render custom columns in list tables
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @param string $name the column name
	 * @param int    $post_id the object ID
	 *
	 * @return void
	 */
	function amnesty_render_custom_columns( string $name, int $post_id ): void {
		if ( 'id' === $name ) {
			echo absint( $post_id );
		}
	}
}

add_action( 'manage_pages_custom_column', 'amnesty_render_custom_columns', 10, 2 );
add_action( 'manage_posts_custom_column', 'amnesty_render_custom_columns', 10, 2 );

if ( ! function_exists( 'amnesty_add_custom_column_css' ) ) {
	/**
	 * Output CSS for custom columns in list tables
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @return void
	 */
	function amnesty_add_custom_column_css(): void {
		echo '<style>.wp-list-table .column-id{width:100px}</style>';
	}
}

add_action( 'admin_head', 'amnesty_add_custom_column_css' );

if ( ! function_exists( 'amnesty_list_table_search' ) ) {
	/**
	 * Add search-by-ID support to list tables
	 *
	 * @package Amnesty\Admin\Options
	 *
	 * @param \WP_Query $query the search query
	 *
	 * @return void
	 */
	function amnesty_list_table_search( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		if ( ! is_numeric( $query->get( 's' ) ) ) {
			return;
		}

		$term = absint( $query->get( 's' ) );

		if ( 0 === $term ) {
			return;
		}

		$query->set( 's', '' );
		$query->set( 'p', $term );
	}
}

add_action( 'pre_get_posts', 'amnesty_list_table_search' );
