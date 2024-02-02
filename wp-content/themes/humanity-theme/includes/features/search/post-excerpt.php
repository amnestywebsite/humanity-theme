<?php

if ( ! function_exists( 'get_first_paragraph' ) ) {
	/**
	 * Retrieve first non-empty paragraph from a HTML string
	 *
	 * @package Amnesty\Search
	 *
	 * @param string $html the html to search
	 *
	 * @return string
	 */
	function get_first_paragraph( string $html ): string {
		if ( ! $html ) {
			return '';
		}

		$doc = new DOMDocument();

		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$doc->formatOutput       = false;
		$doc->substituteEntities = false;
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		libxml_use_internal_errors( true );
		$doc->loadHTML(
			mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ),
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOXMLDECL | LIBXML_NOERROR | LIBXML_NOWARNING
		);
		libxml_use_internal_errors( false );

		$xpath = new DOMXPath( $doc );

		foreach ( $xpath->query( '//p' ) as $para ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$innards = trim( wp_strip_all_tags( $para->textContent ) );

			if ( empty( $innards ) ) {
				continue;
			}

			return wp_kses_post( sprintf( '<p>%s</p>', $innards ) );
		}

		return '';
	}
}

if ( ! function_exists( 'amnesty_fallback_excerpt' ) ) {
	/**
	 * If the manual excerpt is empty, fall back to the first paragraph of post content
	 *
	 * @package Amnesty\Search
	 *
	 * @param string  $excerpt the existing excerpt
	 * @param WP_Post $post    the post object
	 *
	 * @return string
	 */
	function amnesty_fallback_excerpt( string $excerpt, WP_Post $post ): string {
		if ( true !== apply_filters( 'amnesty_should_fallback_excerpt', false ) ) {
			return $excerpt;
		}

		if ( ! empty( $excerpt ) ) {
			return $excerpt;
		}

		$content = apply_filters( 'the_content', get_post_field( 'post_content', $post ) );

		return get_first_paragraph( $content );
	}
}

add_filter( 'amnesty_should_fallback_excerpt', 'is_search' );
add_filter( 'get_the_excerpt', 'amnesty_fallback_excerpt', 10, 2 );
