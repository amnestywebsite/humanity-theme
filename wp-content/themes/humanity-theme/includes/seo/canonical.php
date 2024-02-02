<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_canonical' ) ) {
	/**
	 * Render the canonical href on posts
	 *
	 * @return void
	 */
	function amnesty_render_canonical(): void {
		if ( is_admin() || ! is_single() ) {
			return;
		}

		$canonical = get_post_meta( get_the_ID(), '_yoast_wpseo_canonical', true );

		if ( ! $canonical ) {
			return;
		}

		if ( wp_parse_url( $canonical, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
			return;
		}

		printf( '<link rel="canonical" href="%s">', esc_url( $canonical ) );
	}
}

add_action( 'wp_head', 'amnesty_render_canonical' );

if ( ! function_exists( 'amnesty_wpseo_canonical_filter' ) ) {
	/**
	 * Remove erroneous canonicals from search results/filters
	 *
	 * @package Amnesty
	 *
	 * @param string|null $canonical the canonical URI
	 *
	 * @return string
	 */
	function amnesty_wpseo_canonical_filter( ?string $canonical = '' ): string {
		if ( get_queried_object_id() !== absint( get_option( 'amnesty_search_page' ) ) ) {
			return $canonical ?? '';
		}

		if ( is_paged() ) {
			return '';
		}

		$query_string = query_string_to_array( wp_parse_url( current_url(), PHP_URL_QUERY ) ?: '' );

		if ( ! empty( $query_string ) ) {
			return '';
		}

		return $canonical ?? '';
	}
}

add_filter( 'wpseo_canonical', 'amnesty_wpseo_canonical_filter' );
