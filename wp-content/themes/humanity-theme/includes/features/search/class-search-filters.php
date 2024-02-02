<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_Query;

new Search_Filters();

/**
 * Extra search filters
 *
 * @package Amnesty\Search
 */
class Search_Filters {

	/**
	 * Constructor to add actions
	 */
	public function __construct() {
		add_filter( 'search_rewrite_rules', [ $this, 'rewrite_rules' ] );
		add_action( 'parse_request', [ $this, 'prettify_search' ] );
		add_action( 'pre_get_posts', [ $this, 'maybe_urldecode' ] );
		add_filter( 'wpseo_title', [ $this, 'title_tag' ] );
		add_filter( 'amnesty_search_results_title', [ $this, 'results_title' ], 10, 3 );
		add_filter( 'amnesty_archive_filter_query_var_value', [ $this, 'change_month_labels' ], 10, 2 );
	}

	/**
	 * Register rewrite rule for search pagination with no term
	 *
	 * @param array $rules existing search rewrite rules
	 *
	 * @return array
	 */
	public function rewrite_rules( array $rules ): array {
		$path = trim( str_replace( home_url(), '', amnesty_search_url() ), '/' );

		$search_rules = [
			sprintf( '%s/(?!page)([^/]+)?/?$', $path ) => sprintf( 'index.php?s=$matches[1]', $path ),
			sprintf( '%s/(?!page)([^/]+)/page/([0-9]{1,})/?$', $path ) => sprintf( 'index.php?s=$matches[1]&paged=$matches[2]', $path ),
			sprintf( '%s/page/([0-9]{1,})/?$', $path ) => sprintf( 'index.php?pagename=%s&paged=$matches[1]', $path ),
		];

		return $search_rules + $rules;
	}

	/**
	 * Force search to use a pretty format
	 *
	 * @param \WP $wp the global wp object
	 *
	 * @return void
	 */
	public function prettify_search( \WP $wp ): void {
		if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( ! array_key_exists( 's', $wp->query_vars ) ) {
			return;
		}

		if ( false === apply_filters( 'amnesty_prettify_search_url', true ) ) {
			return;
		}

		$current = current_url();
		$target  = amnesty_search_url();

		if ( isset( $wp->query_vars['s'] ) ) {
			$search = wp_unslash( $wp->query_vars['s'] );
			$search = html_entity_decode( $search );
			$search = rawurldecode( $search );

			if ( false !== strpos( $search, '/' ) ) {
				$search = rawurlencode( $search );
			}

			$target .= trailingslashit( rawurlencode( $search ) );
			$target  = esc_url( $target );
		}

		if ( isset( $wp->query_vars['paged'] ) ) {
			$target .= sprintf( 'page/%s/', $wp->query_vars['paged'] );
		}

		$vars = array_filter( omit( $wp->query_vars, [ 'name', 's', 'paged', 'pagename' ] ) );

		if ( ! empty( $vars ) ) {
			$target = add_query_arg( $vars, $target );
			$target = str_replace( ' ', '%20', $target );
		}

		if ( set_url_scheme( $current, 'https' ) === set_url_scheme( $target, 'https' ) ) {
			return;
		}

		wp_safe_redirect( $target, 302, 'Search' );
		die;
	}

	/**
	 * Maybe URL-decode search terms that are twice-encoded
	 *
	 * Searches containing forward slashes are twice-encoded
	 * to prevent routing 404s on some web servers
	 *
	 * @param WP_Query $query the main query object
	 *
	 * @return void
	 */
	public function maybe_urldecode( WP_Query $query ): void {
		if ( ! $query->is_main_query() || ! $query->get( 's' ) ) {
			return;
		}

		$s = $query->get( 's' );

		if ( false === mb_strpos( $s, '%252F', encoding: 'UTF-8' ) ) {
			return;
		}

		$query->set( 's', rawurldecode( $s ) );
	}

	/**
	 * Change the title tag output if there is no search term
	 *
	 * @param string $title the Yoast generated title
	 *
	 * @return string
	 */
	public function title_tag( string $title ): string {
		// we're only targetting search
		if ( false === strpos( current_url(), amnesty_search_url() ) ) {
			return $title;
		}

		// let's make a search page title that's useful
		$sep = '|';

		if ( function_exists( 'YoastSEO' ) ) {
			$sep = YoastSEO()->helpers->options->get_title_separator();
		}

		/* translators: [front] https://www.amnesty.org/en/search/hey/ search results title. */
		$title = _x( 'Search results', 'search results title', 'amnesty' );
		$title = [ $title ];

		if ( amnesty_get_query_var( 's' ) ) {
			$title[] = $this->get_search_suffix( amnesty_get_query_var( 's' ) );
		}

		if ( amnesty_get_query_var( 'qmonth' ) ) {
			$title[] = $this->get_month_suffix( amnesty_get_query_var( 'qmonth' ) );
		}

		if ( amnesty_get_query_var( 'qyear' ) ) {
			$title[] = $this->get_year_suffix( amnesty_get_query_var( 'qyear' ) );
		}

		$title = apply_filters( 'amnesty_search_results_title_tag', implode( ' ', $title ) );

		$parts = [ $title, get_bloginfo( 'name' ) ];
		$title = implode( " {$sep} ", $parts );
		$title = trim( $title, " \t\n\r\0\x0B{$sep}" );

		return esc_html( $title );
	}

	/**
	 * Change the title tag output if there is no search term
	 *
	 * @param string $title  the Yoast generated title
	 * @param int    $count  the number of found posts
	 * @param string $search the search term
	 *
	 * @return string
	 */
	public function results_title( string $title, int $count, string $search ): string {
		// we're only targetting search
		if ( false === strpos( current_url(), amnesty_search_url() ) ) {
			return $title;
		}

		/* translators: [front] https://www.amnesty.org/en/search/hey/ search results title. %d: the number of search results found */
		$title = sprintf( _n( '%s result', '%s results', $count, 'amnesty' ), number_format_i18n( $count ) );
		$title = [ $title ];

		if ( $search ) {
			$title[] = $this->get_search_suffix( $search );
		}

		if ( amnesty_get_query_var( 'qmonth' ) ) {
			$title[] = $this->get_month_suffix( amnesty_get_query_var( 'qmonth' ) );
		}

		if ( amnesty_get_query_var( 'qyear' ) ) {
			$title[] = $this->get_year_suffix( amnesty_get_query_var( 'qyear' ) );
		}

		$title = apply_filters( 'amnesty_search_results_title_tag', implode( ' ', $title ) );

		return esc_html( $title );
	}

	/**
	 * Transform month number to month name
	 *
	 * @param string $value the query var value
	 * @param string $key   the query var name
	 *
	 * @return string
	 */
	public function change_month_labels( string $value, string $key ): string {
		if ( 'qmonth' !== $key ) {
			return $value;
		}

		$months = amnesty_get_months();

		if ( ! isset( $months[ $value ] ) ) {
			return $value;
		}

		return $months[ $value ];
	}

	/**
	 * Append search term info to title
	 *
	 * @param string $search_string the search query var
	 *
	 * @return string
	 */
	protected function get_search_suffix( string $search_string ): string {
		$sterms = preg_split( '/\s(-\w+)/', $search_string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		$negations     = array_filter( $sterms, fn ( string $t ): bool => '-' === mb_substr( $t, 0, 1, 'UTF-8' ) );
		$non_negations = array_filter( $sterms, fn ( string $t ): bool => '-' !== mb_substr( $t, 0, 1, 'UTF-8' ) );

		$title = [];

		// included terms
		if ( $non_negations ) {
			/* translators: [front] https://www.amnesty.org/en/search/hey/ appended to search results title (n results for...); %s: the search term */
			$title[] = sprintf( _x( 'for “%s”', 'search results title suffix for the search term', 'amnesty' ), implode( ' ', $non_negations ) );
		}

		// excluded terms
		if ( $negations ) {
			/* translators: [front] https://www.amnesty.org/en/search/hey/ appended to search results title (n results for...); %s: the negative search term(s) */
			$title[] = sprintf( _x( 'excluding “%s”', 'search results title suffix for term exclusions', 'amnesty' ), implode( ' ', array_map( fn ( string $t ): string => mb_substr( $t, 1, null, 'UTF-8' ), $negations ) ) );
		}

		return implode( ' ', $title );
	}

	/**
	 * Append month filter info to title
	 *
	 * @param string $month the month query var
	 *
	 * @return string
	 */
	protected function get_month_suffix( string $month ): string {
		$months = amnesty_get_months();

		if ( ! isset( $months[ $month ] ) ) {
			return '';
		}

		return esc_html(
			sprintf(
			/* translators: [front] appended to search results title (n results for...); %s: the month searched for */
				_x( 'for the month of %s', 'search results title suffix for month published', 'amnesty' ),
				esc_html( $months[ $month ] )
			) 
		);
	}

	/**
	 * Append year info to title
	 *
	 * @param string $year the year query var
	 *
	 * @return string
	 */
	protected function get_year_suffix( string $year ): string {
		return esc_html(
			sprintf(
			/* translators: [front] appended to search results title (n results for...); %s: the year searched for */
				_x( 'in the year %s', 'search results title suffix for year published', 'amnesty' ),
				absint( $year )
			) 
		);
	}

}
