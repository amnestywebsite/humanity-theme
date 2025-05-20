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

		$cache_key = md5( $html );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return $cached;
		}

		$doc = new DOMDocument( '1.0', 'utf-8' );

		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$doc->formatOutput       = false;
		$doc->substituteEntities = false;
		$doc->preserveWhiteSpace = true;
		$doc->validateOnParse    = false;

		// ensure string is utf8
		$encoded_content = mb_convert_encoding( $html, 'UTF-8' );
		// encode everything
		$encoded_content = htmlentities( $encoded_content, ENT_NOQUOTES, 'UTF-8' );
		// decode "standard" characters
		$encoded_content = htmlspecialchars_decode( $encoded_content, ENT_NOQUOTES );
		// convert left side of ISO-8859-1 to HTML numeric character reference
		// this was taken from PHP docs for mb_encode_numericentity   vvvvvvvvvvvvvvvvvvvvvvvvv
		$encoded_content = mb_encode_numericentity( $encoded_content, [ 0x80, 0x10FFFF, 0, ~0 ], 'UTF-8' );

		libxml_use_internal_errors( true );
		$doc->loadHTML(
			'<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>' .
			$encoded_content .
			'</body>',
			LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOENT
		);
		libxml_use_internal_errors( false );

		$xpath = new DOMXPath( $doc );

		foreach ( $xpath->query( '//p' ) as $para ) {
			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$innards = trim( wp_strip_all_tags( $para->textContent ) );

			if ( empty( $innards ) ) {
				continue;
			}

			$output = sprintf( '<p>%s</p>', $innards );

			wp_cache_add( $cache_key, $output );
			return wp_kses_post( $output );
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
