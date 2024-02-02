<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_valid_sort_parameters' ) ) {
	/**
	 * All valid post sorting methods on the news index.
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @return array
	 */
	function amnesty_valid_sort_parameters() {
		$sorts = [
			'date-desc'  => /* translators: [front] https://www.amnesty.org/en/latest/ Post sort by option */ __( 'Most Recent', 'amnesty' ),
			'date-asc'   => /* translators: [front] https://www.amnesty.org/en/latest/ Post sort by option */ __( 'Oldest First', 'amnesty' ),
			'title-asc'  => /* translators: [front] https://www.amnesty.org/en/latest/ Post sort by option */ __( 'Title - Ascending', 'amnesty' ),
			'title-desc' => /* translators: [front] https://www.amnesty.org/en/latest/ Post sort by option */ __( 'Title - Descending', 'amnesty' ),
		];

		if ( is_search() ) {
			$sorts = [ 'relevance-desc' => /* translators: [front] https://www.amnesty.org/en/search/hey/ Post sort by option */ __( 'Most Relevant', 'amnesty' ) ] + $sorts;
		}

		// sorting by title makes no sense on a search with keyword(s)
		if ( amnesty_get_query_var( 's' ) ) {
			unset( $sorts['title-asc'], $sorts['title-desc'] );
		}

		return apply_filters( 'amnesty_valid_search_parameters', $sorts );
	}
}

add_filter( 'get_pagenum_link', 'amnesty_add_sort_parameters' );

if ( ! function_exists( 'amnesty_add_sort_parameters' ) ) {
	/**
	 * Adds sorting parameters to the url passed to the function.
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param string $current_url - Url to append sort query to.
	 *
	 * @return string
	 */
	function amnesty_add_sort_parameters( $current_url = '' ) {
		$sort = get_query_var( 'sort' );

		if ( ! $sort ) {
			return remove_query_arg( 'sort', $current_url );
		}

		return add_query_arg( compact( 'sort' ), $current_url );
	}
}

/**
 * Register sort query var with WP
 */
add_action( 'init', fn () => $GLOBALS['wp']->add_query_var( 'sort' ) );

if ( ! function_exists( 'amnesty_sort_posts_in_main_query' ) ) {
	/**
	 * Applies the filter to the query if its valid.
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param WP_Query $query - Current WordPress query.
	 *
	 * @return void
	 */
	function amnesty_sort_posts_in_main_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$sort = get_query_var( 'sort' );

		if ( ! $sort ) {
			return;
		}

		$valid_sort_parameters = array_keys( amnesty_valid_sort_parameters() );

		if ( ! in_array( $sort, $valid_sort_parameters, true ) ) {
			return;
		}

		list( $orderby, $order ) = explode( '-', $sort );

		$query->set( 'order', $order );
		$query->set( 'orderby', $orderby );
	}
}

add_action( 'pre_get_posts', 'amnesty_sort_posts_in_main_query' );
