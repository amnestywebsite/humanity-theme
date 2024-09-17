<?php

declare( strict_types = 1 );

add_filter( 'block_type_metadata_settings', 'amnesty_override_post_terms_render' );

if ( ! function_exists( 'amnesty_override_post_terms_render' ) ) {
	/**
	 * Overrides the render method of core/post-terms
	 *
	 * @param array<string,mixed> $settings the block settings
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_override_post_terms_render( array $settings ): array {
		if ( 'core/post-terms' === $settings['name'] ) {
			$settings['render_callback'] = 'amnesty_render_block_core_post_terms';
		}

		return $settings;
	}
}

if ( ! function_exists( 'amnesty_render_block_core_post_terms' ) ) {
	/**
	 * Renders the `core/post-terms` block on the server.
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string Rendered block markup.
	 *
	 * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
	 */
	function amnesty_render_block_core_post_terms( $attributes, $content, $block ) {
		if ( ! isset( $block->context['postId'] ) || ! isset( $attributes['term'] ) ) {
			return '';
		}

		if ( ! is_taxonomy_viewable( $attributes['term'] ) ) {
			return '';
		}

		$post_terms = get_the_terms( $block->context['postId'], $attributes['term'] );
		if ( is_wp_error( $post_terms ) || empty( $post_terms ) ) {
			return '';
		}

		// Limit array to 1 entry for each term
		$post_terms = array_slice( $post_terms, 0, 1, true );

		$classes = array( 'taxonomy-' . $attributes['term'] );
		if ( isset( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}
		if ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) {
			$classes[] = 'has-link-color';
		}

		$separator = '';

		$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => implode( ' ', $classes ) ) );

		$prefix = "<div " .  wp_kses_data( $wrapper_attributes ) . ">";
		if ( isset( $attributes['prefix'] ) && $attributes['prefix'] ) {
			$prefix .= '<span class="wp-block-post-terms__prefix">' . $attributes['prefix'] . '</span>';
		}

		$suffix = '</div>';
		if ( isset( $attributes['suffix'] ) && $attributes['suffix'] ) {
			$suffix = '<span class="wp-block-post-terms__suffix">' . $attributes['suffix'] . '</span>' . $suffix;
		}

		return (
			'<div ' . $wrapper_attributes . '>' .
				'<a href="' . esc_attr( get_term_link( $post_terms[0] ) ) . '" rel="tag">' . esc_html( $post_terms[0]->name ) . '</a>' .
			'</div>'
		);
	}
}
