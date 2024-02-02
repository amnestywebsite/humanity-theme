<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_taxonomy_query_vars' ) ) {
	/**
	 * Register query vars for taxonomy names for the RSS feeds
	 *
	 * @package Amnesty\RSS
	 *
	 * @global $wp
	 *
	 * @return void
	 */
	function amnesty_register_taxonomy_query_vars(): void {
		global $wp;

		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );

		foreach ( $taxonomies as $taxonomy ) {
			$wp->add_query_var( sprintf( '%s_name', $taxonomy->query_var ) );
		}
	}
}

add_action( 'init', 'amnesty_register_taxonomy_query_vars' );

if ( ! function_exists( 'amnesty_add_taxonomy_filters_to_rss' ) ) {
	/**
	 * Allow filtering by taxonomy term on the RSS feeds
	 *
	 * @package Amnesty\RSS
	 *
	 * @param WP_Query $query the query to filter
	 *
	 * @return void
	 */
	function amnesty_add_taxonomy_filters_to_rss( WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_feed() ) {
			return;
		}

		$taxonomies  = get_taxonomies( [ 'public' => true ], 'objects' );
		$query_taxes = [];

		foreach ( $taxonomies as $taxonomy ) {
			$qvar = get_query_var( sprintf( '%s_name', $taxonomy->query_var ) );

			if ( ! $qvar ) {
				continue;
			}

			$query_taxes[] = [
				'taxonomy' => $taxonomy->name,
				'field'    => 'slug',
				'terms'    => [ $qvar ],
			];
		}

		if ( ! empty( $query_taxes ) ) {
			$query->set(
				'tax_query',
				[
					'relation' => 'AND',
					$query_taxes,
				]
			);
		}
	}
}

add_action( 'pre_get_posts', 'amnesty_add_taxonomy_filters_to_rss' );
