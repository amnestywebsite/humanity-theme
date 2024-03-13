<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_wp_kses_post_allowed_html' ) ) {
	/**
	 * Global allowed HTML tag/attribute overrides
	 *
	 * @see wp_kses_allowed_html
	 *
	 * @param array<string,mixed> $tags    the existing allowed tags/attributes
	 * @param string              $context the kses context
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_wp_kses_post_allowed_html( array $tags, string $context ): array {
		if ( 'post' !== $context ) {
			return $tags;
		}

		if ( isset( $tags['source'] ) ) {
			return $tags;
		}

		$tags['source'] = _wp_add_global_attributes(
			[
				'media' => true,
				'src'   => true,
				'type'  => true,
			]
		);

		return $tags;
	}
}

add_filter( 'wp_kses_allowed_html', 'amnesty_wp_kses_post_allowed_html', 100, 2 );
