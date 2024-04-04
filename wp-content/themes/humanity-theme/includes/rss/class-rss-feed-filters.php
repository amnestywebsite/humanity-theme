<?php

declare( strict_types = 1 );

namespace Amnesty;

new RSS_Feed_Filters();

/**
 * Apply various filters to the RSS feed
 *
 * @package Amnesty\RSS
 */
class RSS_Feed_Filters {

	/**
	 * Bind hooks
	 */
	public function __construct() {
		add_filter( 'the_category_rss', [ $this, 'add_custom_terms' ], 10, 2 );
		add_filter( 'get_object_terms', [ $this, 'remove_report_terms' ], 10, 3 );
	}

	/**
	 * Add custom taxonomy terms to feed items
	 *
	 * @param string $term_list the term list
	 * @param string $feed_type the feed type (rss/atom/etc)
	 *
	 * @return string
	 */
	public function add_custom_terms( string $term_list, string $feed_type ): string {
		switch ( $feed_type ) {
			case 'atom':
				return $this->add_terms_to_atom( $term_list );
			case 'rdf':
				return $this->add_terms_to_rdf( $term_list );
			default:
				break;
		}

		$terms = wp_get_object_terms( get_the_ID(), 'category' );

		if ( is_wp_error( $terms ) ) {
			$terms = [];
		}

		$terms = array_merge( $terms, $this->get_terms_to_add() );

		if ( empty( $terms ) ) {
			return $term_list;
		}

		$term_groups = [];

		// organise terms by taxonomy
		foreach ( (array) $terms as $term ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			$label    = sanitize_title_with_dashes( $taxonomy->label );

			if ( ! isset( $term_groups[ $label ] ) ) {
				$term_groups[ $label ] = [];
			}

			$term_groups[ $label ][] = $term;
		}

		$newlist = '';

		foreach ( $term_groups as $taxonomy => $terms ) {
			$newlist .= sprintf( "\t\t<amn:%s>\n", sanitize_term_field( 'taxonomy', $taxonomy, $term->term_id, $term->taxonomy, 'rss' ) );

			foreach ( $terms as $term ) {
				$newlist .= sprintf( "\t\t\t<amn:id name=\"%s\">%d</amn:id>\n", sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'rss' ), absint( $term->term_id ) );
			}

			$newlist .= sprintf( "\t\t</amn:%s>\n", sanitize_term_field( 'taxonomy', $taxonomy, $term->term_id, $term->taxonomy, 'rss' ) );
		}

		return $newlist;
	}

	/**
	 * Add custom taxonomy terms to atom feed items
	 *
	 * @param string $term_list the original term list
	 *
	 * @return string
	 */
	protected function add_terms_to_atom( string $term_list ): string {
		$terms = $this->get_terms_to_add();

		if ( ! $terms ) {
			return $term_list;
		}

		$term_names = $this->get_term_names( $terms, 'raw' );

		$newlist = '';

		foreach ( $term_names as $term_name ) {
			$newlist .= sprintf( '<category scheme="%1$s" term="%2$s" />', esc_attr( get_bloginfo_rss( 'url' ) ), esc_attr( $term_name ) );
		}

		return $newlist;
	}

	/**
	 * Add custom taxonomy terms to rdf feed items
	 *
	 * @param string $term_list the original term list
	 *
	 * @return string
	 */
	protected function add_terms_to_rdf( string $term_list ): string {
		$terms = $this->get_terms_to_add();

		if ( ! $terms ) {
			return $term_list;
		}

		$term_names = $this->get_term_names( $terms, 'rss' );

		$newlist = '';

		foreach ( $term_names as $term_name ) {
			$newlist .= "\t\t<dc:subject><![CDATA[$term_name]]></dc:subject>\n";
		}

		return $newlist;
	}

	/**
	 * Prevent "Report" terms (location taxonomy) from outputting in RSS feeds
	 *
	 * @param array<int,\WP_Term> $terms      list of found terms
	 * @param array<int,int>      $object_ids list of post IDs in feed
	 * @param array<int,string>   $taxonomies list of target taxonomies
	 *
	 * @return array<int,\WP_Term>
	 */
	public function remove_report_terms( array $terms, array $object_ids, array $taxonomies ): array {
		// we're only targetting the RSS feed
		if ( ! did_action( 'rss_tag_pre' ) ) {
			return $terms;
		}

		$location_slug = amnesty_get_taxonomy_slug( 'location' );

		// don't need to add this filter
		if ( count( $taxonomies ) && ! in_array( $location_slug, $taxonomies, true ) ) {
			return $terms;
		}

		$filtered = [];

		foreach ( $terms as $term ) {
			// we don't need to check this term
			if ( $term->taxonomy !== $location_slug ) {
				$filtered[] = $term;
				continue;
			}

			// it's a report - don't include it in the results
			if ( 'report' === get_term_meta( $term->term_id, 'type', true ) ) {
				continue;
			}

			$filtered[] = $term;
		}

		return $filtered;
	}

	/**
	 * Retrieve taxonomy terms for addition to feeds
	 *
	 * @return array<int,\WP_Term>
	 */
	protected function get_terms_to_add(): array {
		$taxes = get_taxonomies(
			[
				'public'   => true,
				'_builtin' => false,
			]
		);

		$terms = wp_get_object_terms( get_the_ID(), array_values( $taxes ) );

		if ( is_wp_error( $terms ) ) {
			return [];
		}

		return $terms;
	}

	/**
	 * Retrieve sanitised term names for feed addition
	 *
	 * @param array<int,\WP_Term> $terms  the list of terms
	 * @param string              $filter the sanitisation filter
	 *
	 * @return array<int,string>
	 */
	protected function get_term_names( array $terms, string $filter ): array {
		$term_names = [];

		foreach ( (array) $terms as $term ) {
			$term_names[] = sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, $filter );
		}

		return array_unique( $term_names );
	}

}
