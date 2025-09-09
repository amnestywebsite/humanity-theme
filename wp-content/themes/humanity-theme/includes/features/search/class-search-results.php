<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_Query;
use WP_Tax_Query;
use WP_Term;

new Search_Results();

/**
 * Search results modifications
 */
class Search_Results {

	/**
	 * Bind hooks
	 */
	public function __construct() {
		add_action( 'pre_get_posts', [ $this, 'boot' ], 9 );
	}

	/**
	 * Undocumented function
	 *
	 * @param \WP_Query $query The main query object
	 *
	 * @return void
	 */
	public function boot( WP_Query $query ): void {
		if ( $this->should_filter_search( $query ) ) {
			add_filter( 'posts_request', [ $this, 'filter_posts_request' ], 10, 2 );
		}
	}

	/**
	 * Rewrite search query SQL if documents are to be searched for
	 *
	 * @param string    $sql   The existing SQL query
	 * @param \WP_Query $query The Query object
	 *
	 * @return string
	 */
	public function filter_posts_request( string $sql, WP_Query $query ): string {
		/**
		 * The global db object
		 *
		 * @var \wpdb $wpdb the db object
		 */
		global $wpdb;

		if ( ! $this->should_filter_search( $query ) ) {
			return $sql;
		}

		$custom_query = amnesty_get_searchpage_query_object( false );

		return $wpdb->remove_placeholder_escape( $custom_query->get_sql() );
	}

	/**
	 * Build the JOIN/WHERE portion of the SQL query that
	 * directly relates to the selected taxonomy terms.
	 *
	 * @param array<string,array<int,int>> $terms The taxonomy terms
	 *
	 * @return array<string,string>
	 */
	final public function get_tax_query( array $terms ): array {
		global $wpdb;

		static $tax_query;

		if ( ! isset( $tax_query ) ) {
			$tax_query = new WP_Tax_Query( $this->build_tax_args( $terms ) );
			$tax_query = $tax_query->get_sql( $wpdb->posts, 'id' );
		}

		return $tax_query;
	}

	/**
	 * Check whether the searchpage has active taxonomy filters
	 * Build tax_query argument list for them, if so
	 *
	 * @param array<string,array<int,int>> $terms The taxonomy terms
	 *
	 * @return array<mixed>
	 */
	final public function build_tax_args( array $terms ): array {
		$tax_query = [];

		foreach ( $terms as $taxonomy => $term_ids ) {
			$tax_query[] = [
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => $term_ids,
			];
		}

		if ( empty( $tax_query ) ) {
			return $tax_query;
		}

		return [
			'relation' => 'AND',
			...$tax_query,
		];
	}

	/**
	 * Retrieve term ids from request and convert to slugs
	 *
	 * @return array<string,array<int,string>>
	 */
	final protected function term_ids_to_slugs(): array {
		$terms = [];

		foreach ( get_taxonomies( [ 'public' => true ] ) as $tax_name ) {
			$tax_qvar = query_var_to_array( "q{$tax_name}" );

			if ( ! $tax_qvar ) {
				continue;
			}

			$objects = get_terms(
				[
					'taxonomy' => $tax_name,
					'include'  => $tax_qvar,
				]
			);

			$terms[ $tax_name ] = array_map( fn ( WP_Term $t ): string => $t->slug, $objects );
		}

		return $terms;
	}

	/**
	 * Convert list of taxonomy slugs to term ids
	 *
	 * @param array<string,array<int,string>> $term_list The list of tax=>terms slugs
	 *
	 * @return array<string,array<int,int>>
	 */
	final protected function slugs_to_term_ids( array $term_list ): array {
		$terms = [];

		foreach ( $term_list as $tax_slug => $term_slugs ) {
			$objects = get_terms(
				[
					'taxonomy' => $tax_slug,
					'slug'     => $term_slugs,
				]
			);

			$terms[ $tax_slug ] = array_map( fn ( WP_Term $t ): int => $t->term_id, $objects );
		}

		return $terms;
	}

	/**
	 * Check whether we should add PDFs to the query
	 *
	 * @param \WP_Query $query The query to check
	 *
	 * @return bool
	 */
	final protected function should_filter_search( WP_Query $query ): bool {
		if ( is_admin() || ! is_search() || ! $query->is_main_query() ) {
			return false;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return false;
		}

		return true;
	}

}
