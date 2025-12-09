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

		// why this isn't default-available, i don't know
		$tags['a'] = array_merge( $tags['a'], [ 'hreflang' => true ] );

		return $tags;
	}
}

add_filter( 'wp_kses_allowed_html', 'amnesty_wp_kses_post_allowed_html', 100, 2 );

if ( ! function_exists( 'amnesty_wp_kses_post_allow_style_tag' ) ) {
	/**
	 * Allow the <style> tag to be output
	 *
	 * @param array<string,mixed> $tags    allowed tags
	 * @param string              $context the kses context
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_wp_kses_post_allow_style_tag( array $tags, string $context ): array {
		if ( 'post' === $context ) {
			$tags['style'] = [];
		}

		return $tags;
	}
}
