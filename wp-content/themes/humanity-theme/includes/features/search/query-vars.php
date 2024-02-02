<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_search_query_vars' ) ) {
	/**
	 * Register our query vars with WP
	 *
	 * @package Amnesty\Search
	 *
	 * @return void
	 */
	function amnesty_search_query_vars() {
		/**
		 * Access query vars from global $wp
		 *
		 * @var \WP $wp
		 */
		global $wp;

		foreach ( [ 'year', 'month' ] as $var ) {
			$wp->add_query_var( "q{$var}" );
		}

		$wp->add_query_var( 'sort' );
	}
}

add_action( 'init', 'amnesty_search_query_vars' );
