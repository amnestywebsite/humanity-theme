<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_prevent_sticky_posts_counting' ) ) {
	/**
	 * Prevent sticky posts counting into the per page count
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param WP_Query $query the query object
	 *
	 * @return void
	 */
	function amnesty_prevent_sticky_posts_counting( WP_Query $query ): void {
		// we're only targeting the news index
		if ( ! is_home() || ! $query->is_main_query() ) {
			return;
		}

		// we're handling all the sticky posts on page one
		if ( get_query_var( 'paged' ) ) {
			return;
		}

		$sticky_posts = array_filter( array_map( 'absint', get_option( 'sticky_posts' ) ?: [] ) );

		$columns = 4;
		$count   = absint( get_option( 'posts_per_page' ) );
		$sticky  = count( $sticky_posts );

		// there's fewer than a page's worth of sticky posts
		if ( $count - $sticky >= 1 ) {
			// reduce the per-page limit by the number of sticky posts (e.g. columns 3, count 12 - sticky 3, per-page 9)
			$query->set( 'posts_per_page', $count - $sticky );
			$query->set( 'ignore_sticky_posts', true );
			$query->set( 'post__not_in', $sticky_posts );
			return;
		}

		// there's a round number of sticky posts, set the per-page limit to one row (e.g. columns 3, sticky 15, per-page 3)
		if ( 0 === $sticky % $columns ) {
			$query->set( 'posts_per_page', $columns );
			return;
		}

		// there's an unbalanced number of sticky posts, set the per-page limit to the row remainder (e.g. columns 3, sticky 13, per-page 2)
		$query->set( 'posts_per_page', $columns - ( $sticky % $columns ) );
	}
}

add_action( 'pre_get_posts', 'amnesty_prevent_sticky_posts_counting' );

if ( ! function_exists( 'amnesty_prepend_sticky_posts' ) ) {
	/**
	 * Prepend sticky posts onto page one of the news index query
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param array    $posts the collection of posts
	 * @param WP_Query $query the query object
	 *
	 * @return array
	 */
	function amnesty_prepend_sticky_posts( array $posts, WP_Query $query ): array {
		// we're only targeting the news index
		if ( ! is_home() && ! $query->is_main_query() ) {
			return $posts;
		}

		// we're handling all the sticky posts on page one
		if ( get_query_var( 'paged' ) ) {
			return $posts;
		}

		// query isn't set to ignore sticky posts, do nothing
		if ( ! $query->get( 'ignore_sticky_posts' ) ) {
			return $posts;
		}

		// prevent infinite loop
		if ( __FUNCTION__ === $query->get( 'AMNESTY_FILTER' ) ) {
			return $posts;
		}

		$sticky = array_filter( array_map( 'absint', get_option( 'sticky_posts' ) ?: [] ) );

		// no stickies
		if ( empty( $sticky ) ) {
			return $posts;
		}

		$sticky = new WP_Query(
			[
				'post__in'            => $sticky,
				'post_status'         => 'publish',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'AMNESTY_FILTER'      => __FUNCTION__,
			] 
		);

		// no found stickies
		if ( ! count( $sticky->posts ) ) {
			return $posts;
		}

		return array_merge( $sticky->posts, $posts );
	}
}

// prepend sticky posts
add_filter( 'the_posts', 'amnesty_prepend_sticky_posts', 10, 2 );
