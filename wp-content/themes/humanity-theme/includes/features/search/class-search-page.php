<?php

declare( strict_types = 1 );

namespace Amnesty;

use ReflectionMethod;
use WP_Post;
use WP_Query;
use WP_Tax_Query;

/**
 * Search Page query logic
 *
 * @package Amnesty\Search
 */
class Search_Page {

	/**
	 * The query object
	 *
	 * @var \WP_Query|null
	 */
	protected ?WP_Query $query = null;

	/**
	 * Count of found search results
	 *
	 * @var int
	 */
	protected int $found_rows = 0;

	/**
	 * List of found posts
	 *
	 * @var array<int,\WP_Post>
	 */
	protected array $found_posts;

	/**
	 * Instantiate class
	 *
	 * @param bool $execute whether to trigger query on instantiation
	 */
	public function __construct( bool $execute = true ) {
		if ( $execute ) {
			$this->get_results();
		}
	}

	/**
	 * Retrieve the query object for the search page
	 *
	 * @return WP_Query|null
	 */
	public function get_wp_query(): ?WP_Query {
		if ( is_null( $this->query ) ) {
			$this->query = new WP_Query( $this->get_query_vars() );

			$search_page_id = absint( get_option( 'amnesty_search_page' ) );

			$this->query->posts = array_filter( $this->query->posts, fn ( $p ): bool => $p->ID !== $search_page_id );
			$this->query->posts = array_values( $this->query->posts );
		}

		return $this->query;
	}

	/**
	 * Query the database for the search results
	 *
	 * @return array<int,\WP_Post>
	 */
	public function get_results(): array {
		$query = $this->get_wp_query();

		$this->found_posts = $query->posts;
		$this->found_rows  = $query->found_posts;

		return $this->found_posts;
	}

	/**
	 * Retrieve count of found rows
	 *
	 * @return int
	 */
	public function get_count(): int {
		return (int) apply_filters( 'amnesty_searchpage_count', $this->found_rows );
	}

	/**
	 * Retrieve WP_Query arguments for the searchpage
	 *
	 * @return array<string,mixed>
	 */
	public function get_query_vars(): array {
		$post_types = apply_filters( 'amnesty_list_query_post_types', [ 'page', 'post' ] );
		$post_types = array_values( array_filter( array_unique( (array) $post_types ) ) );
		$order_vars = $this->get_order_vars();

		$vars = [
			'post_type' => $post_types,
			'orderby'   => $order_vars['orderby'],
			'order'     => $order_vars['order'],
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			'tax_query' => $this->build_tax_args(),
			'year'      => absint( amnesty_get_query_var( 'qyear' ) ),
			'monthnum'  => absint( amnesty_get_query_var( 'qmonth' ) ),
			's'         => get_query_var( 's' ),
		];

		if ( defined( 'SP_FILE' ) ) {
			$vars['SHAREPOINT_SEARCH_FILTERS'] = true;
		}

		return array_filter( $vars );
	}

	/**
	 * Retrieve core/query block args for the searchpage
	 *
	 * @return array<string,mixed>
	 */
	public function get_block_vars(): array {
		$post_types = apply_filters( 'amnesty_list_query_post_types', [ 'page', 'post' ] );
		$post_types = array_values( array_filter( array_unique( (array) $post_types ) ) );
		$order_vars = $this->get_order_vars();

		return [
			'inherit'  => false,
			'perPage'  => absint( get_option( 'posts_per_page' ) ),
			'pages'    => 0,
			'offset'   => 0,
			'postType' => $post_types,
			'order'    => $order_vars['order'],
			'orderby'  => $order_vars['orderby'],
			'author'   => '',
			'search'   => '', // if there's a term, we'll be on a different template
			'exclude'  => [], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'sticky'   => false,
			'taxQuery' => $this->build_tax_args_for_query_block(),
		];
	}

	/**
	 * Build the SQL query for the searchpage
	 *
	 * @return string
	 */
	public function get_sql(): string {
		$search_query = new WP_Query();
		$search_args  = [
			'exact'    => amnesty_validate_boolish( get_query_var( 'exact' ) ),
			's'        => sanitize_text_field( rawurldecode( get_query_var( 's' ) ) ),
			'sentence' => amnesty_validate_boolish( get_query_var( 'sentence' ) ),
		] + $this->get_order_vars();

		$sql = implode(
			' ',
			[
				$this->get_select_sql(),
				$this->get_join_sql(),
				$this->get_where_sql( $search_query, $search_args ),
				$this->get_group_by_sql(),
				$this->get_order_by_sql( $search_query, $search_args ),
				$this->get_limit_sql(),
			]
		);

		return (string) apply_filters( 'amnesty_searchpage_query', $sql, $search_query, $search_args, $this );
	}

	/**
	 * Build the SELECT portion of the SQL query
	 *
	 * @return string
	 */
	public function get_select_sql(): string {
		global $wpdb;

		$select = "SELECT SQL_CALC_FOUND_ROWS {$wpdb->posts}.* FROM {$wpdb->posts}";
		$select = (string) apply_filters( 'amnesty_searchpage_query_select', $select, $this );

		return $select;
	}

	/**
	 * Build the JOIN portion of the SQL query
	 *
	 * @return string
	 */
	public function get_join_sql(): string {
		$join = $this->get_tax_query()['join'];
		$join = (string) apply_filters( 'amnesty_searchpage_query_join', $join, $this );

		return $join;
	}

	/**
	 * Build the WHERE portion of the SQL query
	 *
	 * @param \WP_Query            $query       temporary query for building search SQL
	 * @param array<string,string> $search_args arguments relating to search term(s)
	 *
	 * @return string
	 */
	public function get_where_sql( WP_Query $query, array &$search_args ): string {
		$taxonomies = $this->get_tax_query()['where'];
		$year       = $this->get_where_year_sql();
		$month      = $this->get_where_month_sql();
		$post_types = $this->get_where_post_type_sql();
		$search     = $this->get_where_search_sql( $query, $search_args );

		$where = 'WHERE 1=1' . $taxonomies . ' ' . $year . ' ' . $month . ' ' . $post_types . ' ' . $search;

		return (string) apply_filters( 'amnesty_searchpage_query_where', $where, $this );
	}

	/**
	 * Build the WHERE SQL for the year query var
	 *
	 * @return string
	 */
	public function get_where_year_sql(): string {
		global $wpdb;

		$year = '';

		if ( amnesty_get_query_var( 'qyear' ) ) {
			$year = $wpdb->prepare( "AND year({$wpdb->posts}.post_date) = %d", absint( amnesty_get_query_var( 'qyear' ) ) );
		}

		return $year;
	}

	/**
	 * Build the WHERE SQL for the month query var
	 *
	 * @return string
	 */
	public function get_where_month_sql(): string {
		global $wpdb;

		$month = '';

		if ( amnesty_get_query_var( 'qmonth' ) ) {
			$month = $wpdb->prepare( "AND month({$wpdb->posts}.post_date) = %d", amnesty_get_query_var( 'qmonth' ) );
		}

		return $month;
	}

	/**
	 * Build the WHERE SQL for post types
	 *
	 * @return string
	 */
	public function get_where_post_type_sql(): string {
		global $wpdb;

		$post_types = apply_filters( 'amnesty_list_query_post_types', [ 'page', 'post' ] );
		$post_types = array_values( array_filter( array_unique( (array) $post_types ) ) );

		$post_type_where = '';

		if ( 1 === count( $post_types ) ) {
			$post_type_where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", $post_types[0] );
		} else {
			$post_type_where = "{$wpdb->posts}.post_type IN ('" . implode( "', '", esc_sql( $post_types ) ) . "')";
		}

		$post_type_where .= $wpdb->prepare( " AND {$wpdb->posts}.post_status = %s", 'publish' );

		return (string) apply_filters(
			'amnesty_searchpage_query_where_post_type',
			sprintf( 'AND (%s)', $post_type_where ),
			$post_type_where
		);
	}

	/**
	 * Build the WHERE SQL for search term(s)
	 *
	 * @param \WP_Query            $query       temporary query for building search SQL
	 * @param array<string,string> $search_args arguments relating to search term(s)
	 *
	 * @return string
	 */
	public function get_where_search_sql( WP_Query $query, array &$search_args ): string {
		if ( ! $search_args['s'] ) {
			return '';
		}

		$parse_search = new ReflectionMethod( $query, 'parse_search' );
		$parse_search->setAccessible( true );
		$search_where = $parse_search->invokeArgs( $query, [ &$search_args ] );

		return $search_where;
	}

	/**
	 * Build the GROUP BY portion of the SQL query
	 *
	 * @return string
	 */
	public function get_group_by_sql(): string {
		global $wpdb;

		$groupby = "GROUP BY {$wpdb->posts}.id";
		$groupby = (string) apply_filters( 'amnesty_searchpage_query_group_by', $groupby, $this );

		return $groupby;
	}

	/**
	 * Build the ORDER BY portion of the SQL query
	 *
	 * @param \WP_Query            $query       temporary query for building search SQL
	 * @param array<string,string> $search_args arguments relating to search term(s)
	 *
	 * @return string
	 */
	public function get_order_by_sql( WP_Query $query, array $search_args ): string {
		global $wpdb;

		$orderby = 'post_date';
		if ( 'title' === $search_args['orderby'] ) {
			$orderby = 'post_title';
		}

		if ( ! $search_args['s'] ) {
			$orderby_sql = sprintf(
				'ORDER BY %s.%s %s',
				esc_sql( $wpdb->posts ),
				esc_sql( $orderby ),
				esc_sql( $search_args['order'] ),
			);

			return (string) apply_filters( 'amnesty_searchpage_query_order_by', $orderby_sql, $this );
		}

		if ( 'relevance' === $search_args['orderby'] ) {
			$parse_orderby = new ReflectionMethod( $query, 'parse_search_order' );
			$parse_orderby->setAccessible( true );
			$search_order = $parse_orderby->invokeArgs( $query, [ &$search_args ] );

			$orderby_sql = sprintf( 'ORDER BY %s, %s.post_date %s', $search_order, esc_sql( $wpdb->posts ), esc_sql( $search_args['order'] ) );
			$orderby_sql = (string) apply_filters( 'amnesty_searchpage_query_order_by', $orderby_sql, $this );

			return $orderby_sql;
		}

		$orderby_sql = sprintf( 'ORDER BY %s.post_date %s', esc_sql( $wpdb->posts ), esc_sql( $search_args['order'] ) );
		$orderby_sql = (string) apply_filters( 'amnesty_searchpage_query_order_by', $orderby_sql, $this );

		return $orderby_sql;
	}

	/**
	 * Retrieve ORDER BY clause variables
	 *
	 * @return array<string,string>
	 */
	public function get_order_vars(): array {
		$orderby = 'date';
		$order   = 'DESC';

		// default to relevance if there's a search term
		if ( amnesty_get_query_var( 's' ) ) {
			$orderby = 'relevance';
		}

		if ( amnesty_get_query_var( 'sort' ) ) {
			$valid_sort_parameters = array_keys( amnesty_valid_sort_parameters() );

			if ( in_array( amnesty_get_query_var( 'sort' ), $valid_sort_parameters, true ) ) {
				list( $orderby, $order ) = explode( '-', amnesty_get_query_var( 'sort' ) );
			}
		}

		return compact( 'orderby', 'order' );
	}

	/**
	 * Build the LIMIT portion of the SQL query
	 *
	 * @return string
	 */
	public function get_limit_sql(): string {
		$count  = max( min( 100, absint( get_option( 'posts_per_page' ) ) ), 1 );
		$offset = ( max( absint( amnesty_get_query_var( 'paged' ) ), 1 ) * $count ) - $count;

		$limit = sprintf( 'LIMIT %d,%d', absint( $offset ), absint( $count ) );
		$limit = (string) apply_filters( 'amnesty_searchpage_query_limit', $limit, $this );

		return $limit;
	}

	/**
	 * Build the JOIN/WHERE portion of the SQL query that
	 * directly relates to the selected taxonomy terms.
	 *
	 * @param bool $cached whether to use cached version or not
	 *
	 * @return array<string,string>
	 */
	public function get_tax_query( bool $cached = true ): array {
		global $wpdb;

		if ( ! $cached ) {
			return ( new WP_Tax_Query( $this->build_tax_args() ) )->get_sql( $wpdb->posts, 'id' );
		}

		static $tax_query;

		if ( ! isset( $tax_query ) ) {
			$tax_query = ( new WP_Tax_Query( $this->build_tax_args() ) )->get_sql( $wpdb->posts, 'id' );
		}

		return $tax_query;
	}

	/**
	 * Check whether the searchpage has active taxonomy filters
	 * Build tax_query argument list for them, if so
	 *
	 * @return array
	 */
	public function build_tax_args(): array {
		$tax_query = [];

		foreach ( get_taxonomies( [ 'public' => true ] ) as $tax_name ) {
			$tax_qvar = query_var_to_array( "q{$tax_name}" );

			if ( ! $tax_qvar ) {
				continue;
			}

			$tax_query[] = [
				'taxonomy' => $tax_name,
				'field'    => 'id',
				'terms'    => $tax_qvar,
			];
		}

		if ( empty( $tax_query ) ) {
			return [];
		}

		return [
			'relation' => 'AND',
			...$tax_query,
		];
	}

	/**
	 * Check whether the searchpage has active taxonomy filters
	 * Build taxQuery argument list for them, if so
	 *
	 * @return array
	 */
	public function build_tax_args_for_query_block(): array {
		$tax_query = [];

		foreach ( get_taxonomies( [ 'public' => true ] ) as $tax_name ) {
			$tax_qvar = query_var_to_array( "q{$tax_name}" );

			if ( ! $tax_qvar ) {
				continue;
			}

			$tax_query[ $tax_name ] = $tax_qvar;
		}

		return array_filter( $tax_query );
	}

}
