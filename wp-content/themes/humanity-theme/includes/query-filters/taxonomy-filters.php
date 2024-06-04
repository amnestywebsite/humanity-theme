<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_add_taxonomy_filter_support_to_query' ) ) {
	/**
	 * Add taxonomy filter support to various index pages
	 *
	 * @package Amnesty\Filters\WPQuery
	 *
	 * @param WP_Query $query the query object
	 *
	 * @return void
	 */
	function amnesty_add_taxonomy_filter_support_to_query( WP_Query $query ) {
		// only modify main query on front end
		if ( ! $query->is_main_query() || is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		// only targetted index pages
		if ( ! $query->is_search() && ! $query->is_home() && ! $query->is_category() ) {
			return;
		}

		// see which taxonomy filters are active
		$taxonomies  = get_taxonomies( [ 'public' => true ] );
		$query_taxes = [];

		foreach ( $taxonomies as $taxonomy ) {
			$qvar = amnesty_get_query_var( "q{$taxonomy}" );

			if ( ! $qvar ) {
				continue;
			}

			if ( ! is_array( $qvar ) ) {
				$qvar = intlist( $qvar );
			}

			$query_taxes[] = [
				'taxonomy'         => $taxonomy,
				'field'            => 'id',
				'terms'            => $qvar,
				'include_children' => true,
			];
		}

		if ( empty( $query_taxes ) ) {
			return;
		}

		$tax_query = [
			'relation' => 'AND',
			...$query_taxes,
		];

		$query->set( 'tax_query', $tax_query );
		$query->tax_query = new WP_Tax_Query( $tax_query );
	}
}

// add taxonomy filter support to various index pages
add_action( 'pre_get_posts', 'amnesty_add_taxonomy_filter_support_to_query' );
