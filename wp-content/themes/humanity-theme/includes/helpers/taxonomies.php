<?php

declare( strict_types = 1 );

// phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh

if ( ! function_exists( 'amnesty_get_object_taxonomies' ) ) {
	/**
	 * Operates like get_object_taxonomies, but only returns Amnesty-registered
	 * taxonomies, sorted by precedence, if available
	 *
	 * @package Amnesty
	 *
	 * @param string|array<int,string>|WP_Post $target object or object type name
	 * @param string                           $output the return type
	 *
	 * @return array<int,string|WP_Taxonomy>
	 */
	function amnesty_get_object_taxonomies( $target, string $output = 'names' ): array {
		$precedence = get_site_option( 'amnesty_network_options' )['taxonomy_precedence'] ?? [];
		$taxonomies = get_object_taxonomies( $target, 'objects' );
		$taxonomies = array_filter( $taxonomies, fn ( WP_Taxonomy $taxonomy ): bool => $taxonomy->amnesty ?? false );

		if ( ! empty( $precedence ) ) {
			uksort(
				$taxonomies,
				function ( string $a, string $b ) use ( $precedence ): int {
					return array_search( $a, $precedence ) <=> array_search( $b, $precedence );
				}
			);
		}

		if ( 'objects' === $output ) {
			return $taxonomies;
		}

		return wp_list_pluck( $taxonomies, 'name' );
	}
}

if ( ! function_exists( 'amnesty_get_prominent_term' ) ) {
	/**
	 * Retrieve a term for an object based on taxonomy precedence, if available
	 *
	 * @package Amnesty
	 *
	 * @param int $post_id the post to retrieve the term for
	 *
	 * @return WP_Term|null
	 */
	function amnesty_get_prominent_term( int $post_id ): ?WP_Term {
		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $post_id ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return apply_filters( 'amnesty_prominent_term', $cached, $post_id );
		}

		global $wpdb;

		$taxonomies  = get_taxonomies( [ 'amnesty' => true ] );
		$tax_prepare = implode( ',', array_fill( 0, count( $taxonomies ), '%s' ) );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$query_sql = $wpdb->prepare(
			"SELECT term.*, tax.* FROM {$wpdb->terms} AS term
			INNER JOIN {$wpdb->term_taxonomy} AS tax ON term.term_id = tax.term_id
			INNER JOIN {$wpdb->term_relationships} AS rel ON tax.term_taxonomy_id = rel.term_taxonomy_id
			WHERE rel.object_id = %d
			AND tax.taxonomy IN ({$tax_prepare})
			AND tax.count > 0",
			array_merge( [ $post_id ], $taxonomies )
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$precedence = get_site_option( 'amnesty_network_options' )['taxonomy_precedence'] ?? [];
		$order_prep = implode( ',', array_fill( 0, count( $precedence ), '%s' ) );

		if ( $order_prep ) {
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$query_sql .= $wpdb->prepare( " order by field(tax.taxonomy, {$order_prep}) asc", $precedence );
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		}

		// phpcs:ignore WordPress.DB
		$term = $wpdb->get_row( $query_sql );

		if ( ! $term ) {
			return apply_filters( 'amnesty_prominent_term', null, $post_id );
		}

		$term = new WP_Term( $term );

		wp_cache_set( $cache_key, $term );

		return apply_filters( 'amnesty_prominent_term', $term, $post_id );
	}
}

if ( ! function_exists( 'amnesty_get_a_post_term' ) ) {
	/**
	 * Retrieve a single taxonomy term for a post
	 *
	 * @package Amnesty
	 *
	 * @param int    $post_id  the post id
	 * @param string $taxonomy the taxonomy, defaults to category
	 * @param array  $args     any additional args to pass to WP_Term_Query
	 *
	 * @return WP_Term|null
	 */
	function amnesty_get_a_post_term( int $post_id, string $taxonomy = 'category', array $args = [] ): ?WP_Term {
		if ( ! amnesty_taxonomy_is_enabled( $taxonomy ) ) {
			return null;
		}

		$terms = wp_get_post_terms( $post_id, $taxonomy, $args );

		if ( is_wp_error( $terms ) ) {
			_doing_it_wrong( __FUNCTION__, esc_html( $terms->get_error_message() ), '1.11.4' );
			return null;
		}

		return array_shift( $terms );
	}
}

if ( ! function_exists( 'amnesty_get_post_terms' ) ) {
	/**
	 * A more performant version of wp_get_object_terms.
	 *
	 * @package Amnesty
	 *
	 * @param int $post_id the post whose terms are to be retrieved
	 *
	 * @return array
	 */
	function amnesty_get_post_terms( $post_id = 0 ) {
		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $post_id ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return apply_filters( 'amnesty_get_post_terms', $cached, $post_id );
		}

		global $wpdb;

		$taxonomies  = get_taxonomies( [ 'amnesty' => true ] );
		$tax_prepare = implode( ',', array_fill( 0, count( $taxonomies ), '%s' ) );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$query_sql = $wpdb->prepare(
			"SELECT term.*, tax.* FROM {$wpdb->terms} AS term
			INNER JOIN {$wpdb->term_taxonomy} AS tax ON term.term_id = tax.term_id
			INNER JOIN {$wpdb->term_relationships} AS rel ON tax.term_taxonomy_id = rel.term_taxonomy_id
			WHERE rel.object_id = %d
			AND tax.taxonomy IN ({$tax_prepare})
			AND tax.count > 0",
			array_merge( [ $post_id ], $taxonomies )
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$precedence = get_site_option( 'amnesty_network_options' )['taxonomy_precedence'] ?? [];
		$order_prep = implode( ',', array_fill( 0, count( $precedence ), '%s' ) );

		if ( $order_prep ) {
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$query_sql .= $wpdb->prepare( " order by field(tax.taxonomy, {$order_prep}) asc, name asc", $precedence );
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		}

		// phpcs:ignore WordPress.DB
		$terms = array_map( fn ( $t ) => new WP_Term( $t ), $wpdb->get_results( $query_sql ) );

		wp_cache_set( $cache_key, $terms );

		return apply_filters( 'amnesty_get_post_terms', $terms, $post_id );
	}
}

if ( ! function_exists( 'get_term_top_most_parent' ) ) {
	/**
	 * Determine the topmost parent of a term.
	 *
	 * @package Amnesty
	 *
	 * @param int    $term_id  the term to find the elder for
	 * @param string $taxonomy the taxonomy to which the term belongs
	 *
	 * @return WP_Term
	 */
	function get_term_top_most_parent( $term_id, $taxonomy = 'category' ) {
		// start from the current term
		$parent = get_term_by( 'id', $term_id, $taxonomy );

		// climb up the hierarchy until we reach a term with parent = 0
		while ( 0 !== $parent->parent ) {
			$term_id = $parent->parent;
			$parent  = get_term_by( 'id', $term_id, $taxonomy );
		}

		return $parent;
	}
}

if ( ! function_exists( 'determine_if_term_is_parent' ) ) {
	/**
	 * Check if a specified term is a parent of a specified term.
	 *
	 * @package Amnesty
	 *
	 * @param int    $current_id the prospective child term.
	 * @param int    $parent_id  the prospective parent term.
	 * @param string $taxonomy   the term taxonomy.
	 *
	 * @return bool
	 */
	function determine_if_term_is_parent( $current_id, $parent_id, $taxonomy = 'category' ) {
		if ( ! $current_id ) {
			return false;
		}

		if ( $current_id === $parent_id ) {
			return true;
		}

		$current = get_term_by( 'id', $current_id, $taxonomy );

		if ( 0 === $current->parent ) {
			return false;
		}

		$parent = get_term_by( 'id', $current->parent, $taxonomy );

		if ( $parent->term_id === $parent_id ) {
			return true;
		}

		while ( 0 !== $parent->parent ) {
			if ( $parent->term_id === $parent_id ) {
				return true;
			}

			$parent = get_term_by( 'id', $parent->parent, $taxonomy );
		}

		if ( $parent->term_id === $parent_id ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'is_current_category' ) ) {
	/**
	 * Check whether the queried object is a category.
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $cat the category to check
	 *
	 * @return bool
	 */
	function is_current_category( WP_Term $cat ) {
		return is_category( $cat->term_id ) || determine_if_term_is_parent( get_queried_object_id(), $cat->term_id );
	}
}

if ( ! function_exists( 'print_category_option' ) ) {
	/**
	 * Print an <option/> for a category item.
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $cat the category to print
	 *
	 * @return void
	 */
	function print_category_option( WP_Term $cat ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( get_term_link( $cat ) ),
			esc_attr( is_current_category( $cat ) ? 'selected' : '' ),
			esc_html( $cat->name )
		);
	}
}

if ( ! function_exists( 'get_term_parent' ) ) {
	/**
	 * Retrieve a term's parent term.
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $term the child term
	 *
	 * @return WP_Term
	 */
	function get_term_parent( WP_Term $term ) {
		if ( ! has_term_parent( $term ) ) {
			return $term;
		}

		return get_term( $term->parent, $term->taxonomy );
	}
}

if ( ! function_exists( 'has_term_parent' ) ) {
	/**
	 * Checks whether a term has a parent.
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $term the term to check
	 *
	 * @return bool
	 */
	function has_term_parent( WP_Term $term ) {
		return 0 !== $term->parent;
	}
}

if ( ! function_exists( 'amnesty_term_link' ) ) {
	/**
	 * Safely return a term link
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $term          the term to retrieve a link for
	 * @param string  $fallback_path a fallback path
	 *
	 * @return string
	 */
	function amnesty_term_link( WP_Term $term, string $fallback_path = '' ) {
		$link = get_term_link( $term );

		if ( is_wp_error( $link ) ) {
			return $fallback_path ? home_url( $fallback_path, 'https' ) : '';
		}

		return $link;
	}
}

if ( ! function_exists( 'amnesty_cross_blog_term_link' ) ) {
	/**
	 * Safely return a term link when the term object
	 * may have come from a different blog
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $term         the term to retrieve a link for
	 * @param bool    $was_switched whether the term was from a different blog
	 *
	 * @return string
	 */
	function amnesty_cross_blog_term_link( WP_Term $term, bool $was_switched = false ): string {
		if ( ! $was_switched ) {
			return amnesty_term_link( $term );
		}

		$term = get_term_by( 'slug', $term->slug, $term->taxonomy );

		if ( ! is_a( $term, WP_Term::class ) ) {
			return '';
		}

		return amnesty_term_link( $term );
	}
}

if ( ! function_exists( 'amnesty_get_locations_by_type' ) ) {
	/**
	 * Get location terms by type.
	 *
	 * The WP_Term_Query "child_of" arg is bugged, so
	 * we can't use it directly. This function takes
	 * a lot of code from the class, but in a way
	 * that actually works.
	 *
	 * @package Amnesty
	 *
	 * @param array $args {
	 *     @type string  $type        the location type
	 *     @type WP_Term $term        a term parent
	 *     @type bool    $show_hidden whether to include "hidden" terms
	 *     @type array   $get_terms   args for get_terms
	 * }
	 *
	 * @return array
	 */
	function amnesty_get_locations_by_type( array $args = [] ): array {
		$slug = get_option( 'amnesty_location_slug' ) ?: 'location';
		$args = wp_parse_args(
			$args,
			[
				'type'        => 'default',
				'term'        => null,
				'show_hidden' => false,
				'get_terms'   => [
					'taxonomy'   => $slug,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query' => [],
				],
			]
		);

		// set basic meta query for get_terms
		if ( '' !== $args['type'] ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$args['get_terms']['meta_query'] = [
				'relation' => 'AND',
				[
					'key'     => 'type',
					'value'   => $args['type'],
					'compare' => '=',
				],
			];
		}

		// ensure type is set correctly
		if ( ! in_array( $args['type'], [ 'default', 'region', 'subregion' ], true ) ) {
			$args['type'] = 'default';
		}

		// no term-specific filter
		if ( null === $args['term'] ) {
			return get_terms( $args['get_terms'] );
		}

		// get the root term
		$top_level = get_term_top_most_parent( $args['term']->term_id, $slug );

		// they only want the root term
		if ( 'region' === $args['type'] ) {
			return [ $top_level ];
		}

		// get all terms of the specific type defined
		$all_terms = get_terms( $args['get_terms'] );

		// only need 2nd level terms (i.e. sub-regions)
		if ( 'subregion' === $args['type'] ) {
			$subregions = _get_term_children( $top_level->term_id, $all_terms, $slug );
			return is_wp_error( $subregions ) ? [ $subregions ] : $subregions;
		}

		$hierarchy = _get_term_hierarchy( $slug );

		// something's gone awry
		if ( empty( $hierarchy[ $top_level->term_id ] ) ) {
			return [];
		}

		// let's now get the right ("default") terms
		$found_terms = [];

		// term provided was not top-level
		if ( $args['term']->term_id !== $top_level->term_id ) {
			$terms = _get_term_children( $args['term']->term_id, $all_terms, $slug );
			if ( ! is_wp_error( $terms ) ) {
				$found_terms = $terms;
			}
		}

		// term provided was top-level
		if ( $args['term']->term_id === $top_level->term_id ) {
			foreach ( $hierarchy[ $top_level->term_id ] as $term_id ) {
				$terms = _get_term_children( $term_id, $all_terms, $slug );

				if ( ! is_wp_error( $terms ) ) {
					$found_terms = array_merge( $found_terms, $terms );
				}

				// if just 2 levels deep
				if ( empty( $terms ) && is_term_in_list( $term_id, $all_terms ) ) {
					array_push( $found_terms, get_term( $term_id ) );
				}
			}
		}

		// alphabetise
		usort( $found_terms, fn ( $b, $a ) => $b->name <=> $a->name );

		return $found_terms;
	}
}

if ( ! function_exists( 'amnesty_get_location_type' ) ) {
	/**
	 * Get a term's "type"
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $location the term to query (optional)
	 *
	 * @return string
	 */
	function amnesty_get_location_type( WP_Term $location = null ): string {
		$location = $location ?: get_queried_object();

		return get_term_meta( $location->term_id, 'type', true ) ?: 'default';
	}
}

if ( ! function_exists( 'amnesty_location_is_region' ) ) {
	/**
	 * Check whether a term has the "region" "type"
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $location the term to check (optional)
	 *
	 * @return bool
	 */
	function amnesty_location_is_region( WP_Term $location = null ): bool {
		return 'region' === amnesty_get_location_type( $location );
	}
}

if ( ! function_exists( 'amnesty_location_is_subregion' ) ) {
	/**
	 * Check whether a term has the "subregion" "type"
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $location the term to check (optional)
	 *
	 * @return bool
	 */
	function amnesty_location_is_subregion( WP_Term $location = null ): bool {
		return 'subregion' === amnesty_get_location_type( $location );
	}
}

if ( ! function_exists( 'amnesty_get_regional_media_contact' ) ) {
	/**
	 * Retrieve a regional contact for a location.
	 * (Regional, in this case, means a sub-region type term)
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $location the location to query
	 *
	 * @return array|null
	 */
	function amnesty_get_regional_media_contact( WP_Term $location = null ): ?array {
		$location = $location ?: get_queried_object();

		// which sub-region?
		if ( amnesty_location_is_region( $location ) ) {
			return null;
		}

		// make sure we're in the right region
		if ( ! amnesty_location_is_subregion( $location ) ) {
			$location = get_term_parent( $location );
		}

		$contact = new WP_Query(
			[
				'post_type'      => get_option( 'media-contact_slug' ) ?: 'media-contact',
				'posts_per_page' => 1,
				'no_found_rows'  => true,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'tax_query'      => [
					[
						'taxonomy' => $location->taxonomy,
						'field'    => 'term_id',
						'terms'    => $location->term_id,
					],
				],
			]
		);

		// no contact found
		if ( ! $contact->have_posts() ) {
			return null;
		}

		return [
			'name'  => apply_filters( 'the_title', $contact->posts[0]->post_title ),
			'title' => get_post_meta( $contact->posts[0]->ID, 'title', true ),
			'phone' => get_post_meta( $contact->posts[0]->ID, 'phone', true ),
			'email' => get_post_meta( $contact->posts[0]->ID, 'email', true ),
		];
	}
}

if ( ! function_exists( 'get_terms_from_query_var' ) ) {
	/**
	 * Get a list of terms from a query variable
	 *
	 * @package Amnesty
	 *
	 * @param string $qvar the query var
	 * @param string $tax  optional taxonomy name
	 *
	 * @return array<int,WP_Term>
	 */
	function get_terms_from_query_var( string $qvar, string $tax = '' ): array {
		$value_list = query_var_to_array( $qvar );

		if ( empty( $value_list ) ) {
			return [];
		}

		$cache_key = md5( sprintf( '%s:%s:%s:%s', __FUNCTION__, $qvar, $tax, wp_json_encode( $value_list ) ) );
		$cached    = wp_cache_get( $cache_key );

		if ( is_array( $cached ) ) {
			return array_map( [ WP_Term::class, 'get_instance' ], $cached );
		}

		$args = [
			'include'  => $value_list,
			'taxonomy' => $tax ?: $qvar,
		];

		$term_list = get_terms( $args );

		if ( is_wp_error( $term_list ) ) {
			return [];
		}

		wp_cache_add( $cache_key, array_map( fn ( WP_Term $term ): int => $term->term_id, $term_list ) );

		return $term_list;
	}
}

if ( ! function_exists( 'amnesty_find_locations' ) ) {
	/**
	 * Possibly locate locations from a search term
	 *
	 * @package Amnesty
	 *
	 * @param string $term the search term
	 *
	 * @return array
	 */
	function amnesty_find_locations( string $term = '' ): array {
		if ( ! $term ) {
			return [];
		}

		$term = sanitize_text_field( rawurldecode( $term ) );
		$slug = get_option( 'amnesty_location_slug' ) ?: 'location';
		$args = [
			'hide_empty' => false,
			'taxonomy'   => $slug,
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'meta_query' => [
				'relation' => 'AND',
				[
					'key'     => 'type',
					'value'   => [ 'region', 'subregion', 'default' ],
					'compare' => 'IN',
				],
			],
		];

		// only one term, or it's all quoted
		if ( false === strpos( $term, ' ' ) || 1 === preg_match( '/^(["\']).*?\1$/', $term ) ) {
			$terms = get_terms( $args + [ 'name__like' => $term ] );
		} else {
			$words = explode( ' ', $term );
			$words = array_filter( $words, fn ( string $w ): bool => '-' !== mb_substr( $w, 0, 1, 'UTF-8' ) );
			$words = array_map( fn ( string $w ): string => preg_replace( '/[^a-z]+/i', '', $w ), $words );
			$words = array_map( 'ucfirst', $words );
			$terms = [];

			if ( function_exists( 'amnesty_get_country_list' ) ) {
				$countries = array_flip( amnesty_get_country_list() );

				foreach ( $words as $word ) {
					if ( ! isset( $countries[ $word ] ) ) {
						continue;
					}

					$terms[] = get_term_by( 'name', $word, $slug );
				}
			}

			$terms = array_filter( $terms );
		}

		if ( is_wp_error( $terms ) ) {
			return [];
		}

		return $terms;
	}
}

if ( ! function_exists( 'amnesty_get_terms_from_query' ) ) {
	/**
	 * Retrieves a collection of terms for a taxonomy from the query string
	 *
	 * @package Amnesty
	 *
	 * @param string $taxonomy the taxonomy *name* (not slug)
	 *
	 * @return array
	 */
	function amnesty_get_terms_from_query( string $taxonomy ): array {
		$tax = get_option( "amnesty_{$taxonomy}_slug" ) ?: $taxonomy;
		$var = sprintf( 'q%s', $tax );

		return get_terms_from_query_var( $var, $taxonomy );
	}
}

if ( ! function_exists( 'is_term_in_list' ) ) {
	/**
	 * Check whether term exists in list of terms
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term|int        $term  the term to find
	 * @param array<int,WP_Term> $terms the list of terms
	 *
	 * @return bool
	 */
	function is_term_in_list( $term, array $terms ): bool {
		$term_id = $term;

		if ( is_a( $term, WP_Term::class ) ) {
			$term_id = $term->term_id;
		}

		$filter = fn ( WP_Term $t ): bool => $t->term_id === $term_id;
		return 0 !== count( array_filter( $terms, $filter ) );
	}
}

if ( ! function_exists( 'amnesty_get_taxonomy_slug' ) ) {
	/**
	 * Retrieve the slug for a taxonomy from its original name
	 *
	 * @package Amnesty
	 *
	 * @param string $taxonomy the taxonomy name
	 *
	 * @return string
	 */
	function amnesty_get_taxonomy_slug( string $taxonomy ): string {
		return get_option( sprintf( 'amnesty_%s_slug', $taxonomy ) ) ?: $taxonomy;
	}
}

if ( ! function_exists( 'amnesty_get_location_label' ) ) {
	/**
	 * Retrieve a label for a term to be shown on search results
	 *
	 * @package Amnesty
	 *
	 * @param WP_Term $location the location object
	 *
	 * @return string
	 */
	function amnesty_get_location_label( WP_Term $location ): string {
		switch ( amnesty_get_location_type( $location ) ) {
			case 'region':
			case 'subregion':
				/* translators: [front] shown as a label for a region in search results */
				$label = __( 'Location Profile', 'amnesty' );
				break;
			default:
				/* translators: [front] shown as a label for a country in search results */
				$label = __( 'Country Profile', 'amnesty' );
				break;
		}

		return $label;
	}
}

if ( ! function_exists( 'amnesty_taxonomy_to_option_list' ) ) {
	/**
	 * Given a taxonomy object, return an array of options for a select field
	 *
	 * @param WP_Taxonomy $taxonomy the taxonomy to retrieve terms from
	 *
	 * @return array<int,string>
	 */
	function amnesty_taxonomy_to_option_list( WP_Taxonomy $taxonomy ): array {
		$args = [ 'taxonomy' => $taxonomy->name ];
		$opts = [];

		$terms = get_terms( $args );

		if ( ! count( $terms ) ) {
			return $opts;
		}

		foreach ( $terms as $term ) {
			$opts[ $term->term_id ] = $term->name;
		}

		return $opts;
	}
}

if ( ! function_exists( 'amnesty_filter_object_taxonomies_callback' ) ) {
	/**
	 * Filter object taxonomies to exclude the categories
	 *
	 * @param WP_Taxonomy $taxonomy the taxonomy object
	 *
	 * @return bool
	 */
	function amnesty_filter_object_taxonomies_callback( WP_Taxonomy $taxonomy ): bool {
		if ( 'post_format' === $taxonomy->name ) {
			return false;
		}

		// reference the original wp_query, as the normal query is overridden on the search page template
		if ( $GLOBALS['wp_the_query']->is_category() && 'category' === $taxonomy->name ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'group_terms_by_initial_ascii_letter' ) ) {
	/**
	 * Group an array of taxonomy terms by their first letters as ASCII
	 *
	 * @param array<int,WP_Term> $terms the terms to sort
	 *
	 * @return array<string,array<int,WP_Term>
	 */
	function group_terms_by_initial_ascii_letter( array $terms ): array {
		$groups = [];

		foreach ( $terms as $term ) {
			$key = $term->name;
			$key = remove_arabic_the( $key );
			$key = mb_substr( $key, 0, 1, 'UTF-8' );
			$key = mb_strtoupper( $key, 'UTF-8' );
			$key = remove_accents( $key );
			$key = remove_arabic_diacritics( $key );

			if ( ! isset( $groups[ $key ] ) ) {
				$groups[ $key ] = [];
			}

			$groups[ $key ][] = $term;
		}

		ksort( $groups );

		return $groups;
	}
}
