<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_body_class' ) ) {
	/**
	 * Add body classes conditionally
	 *
	 * @param array<int,string> $classes existing body classes
	 *
	 * @return array<int,string>
	 */
	function amnesty_body_class( array $classes ): array {
		if ( is_shop() ) {
			$classes[] = 'shop';
			return $classes;
		}

		if ( is_tax() || is_page( 'petitions' ) ) {
			$classes[] = 'has-hero';
			return $classes;
		}

		if ( amnesty_post_has_header() && has_post_thumbnail() && is_singular( [ 'post' ] ) ) {
			$classes[] = 'has-hero';
			return $classes;
		}

		return $classes;
	}
}

add_filter( 'body_class', 'amnesty_body_class' );
