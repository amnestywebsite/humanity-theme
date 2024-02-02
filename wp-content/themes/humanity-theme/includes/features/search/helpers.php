<?php

declare( strict_types = 1 );

use Amnesty\Search_Page;

if ( ! function_exists( 'amnesty_search_url' ) ) {
	/**
	 * Retrieves the fully qualified search URI
	 *
	 * @package Amnesty\Search
	 *
	 * @return string
	 */
	function amnesty_search_url(): string {
		$stored = absint( get_option( 'amnesty_search_page' ) );

		if ( 0 === $stored ) {
			return home_url( '/search/' );
		}

		$permalink = get_permalink( $stored );

		return $permalink ? $permalink : home_url( '/search/' );
	}
}

if ( ! function_exists( 'amnesty_get_searchpage_query_object' ) ) {
	/**
	 * Retrieve the search page query object
	 *
	 * @package Amnesty\Search
	 *
	 * @param bool $execute whether to execute on instantiation
	 *
	 * @return \Amnesty\Search_Page
	 */
	function amnesty_get_searchpage_query_object( bool $execute = true ): Search_Page {
		static $query;

		if ( ! isset( $query ) ) {
			$query = new Search_Page( $execute );
		}

		return $query;
	}
}

if ( ! function_exists( 'amnesty_load_searchpage_query_object' ) ) {
	/**
	 * Load the searchpage query object during request parsing.
	 *
	 * This allows us to trigger an early 404 before the template
	 * is rendered.
	 *
	 * @package Amnesty\Search
	 *
	 * @param WP_Query $query the global WP_Query object
	 *
	 * @return void
	 */
	function amnesty_load_searchpage_query_object( WP_Query $query ): void {
		if ( ! $query->is_main_query() || is_admin() ) {
			return;
		}

		if ( ! is_search() && get_queried_object_id() !== absint( get_option( 'amnesty_search_page' ) ) ) {
			return;
		}

		// initialise the search results query
		$query = amnesty_get_searchpage_query_object();

		// it has found rows and results - it's in-bounds
		if ( $query->get_count() && count( $query->get_results() ) ) {
			return;
		}

		// we're not paged out of range, whether or not there are results
		if ( 1 === absint( amnesty_get_query_var( 'paged' ) ?: 1 ) ) {
			return;
		}

		// there are results for the current page
		if ( count( $query->get_results() ) ) {
			return;
		}

		// there are no results for the current page
		do_404();
	}
}

if ( ! function_exists( 'amnesty_searchpage_has_filters' ) ) {
	/**
	 * Check whether the searchpage has any filters applied
	 *
	 * @package Amnesty\Search
	 *
	 * @return bool
	 */
	function amnesty_searchpage_has_filters(): bool {
		/**
		 * The global query object
		 *
		 * @var WP_Query $wp_query the global query object
		 */
		global $wp_query;

		$all_vars = array_filter( $wp_query->query );

		// on the searchpage, this will always be set
		unset( $all_vars['pagename'] );

		$filtered = array_filter(
			$all_vars,
			fn ( string $variable ): bool => 'q' === substr( $variable, 0, 1 ),
			ARRAY_FILTER_USE_KEY
		);

		return count( $filtered ) > 0;
	}
}

if ( ! function_exists( 'amnesty_get_months' ) ) {
	/**
	 * Retrieve list of localised months
	 *
	 * @package Amnesty\Search
	 *
	 * @return array<string,string>
	 */
	function amnesty_get_months(): array {
		return [
			1  => __( 'January', 'amnesty' ),
			2  => __( 'Febuary', 'amnesty' ),
			3  => __( 'March', 'amnesty' ),
			4  => __( 'April', 'amnesty' ),
			5  => __( 'May', 'amnesty' ),
			6  => __( 'June', 'amnesty' ),
			7  => __( 'July', 'amnesty' ),
			8  => __( 'August', 'amnesty' ),
			9  => __( 'September', 'amnesty' ),
			10 => __( 'October', 'amnesty' ),
			11 => __( 'November', 'amnesty' ),
			12 => __( 'December', 'amnesty' ),
		];
	}
}
