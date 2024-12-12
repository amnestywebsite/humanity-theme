<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_post_meta_block' ) ) {
	/**
	 * Render meta for a post
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string
	 */
	function render_post_meta_block( array $attributes, string $content, WP_Block $block ): string {
		if ( ! isset( $block->context['postId'], $attributes['metaKey'] ) ) {
			return '';
		}

		$post_id = $block->context['postId'];
		$classes = [];

		if ( isset( $attributes['textAlign'] ) ) {
			$classes[] = 'has-text-align-' . $attributes['textAlign'];
		}

		if ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) {
			$classes[] = 'has-link-color';
		}

		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classes ) ] );

		$meta_value = get_post_meta( $post_id, $attributes['metaKey'], $attributes['isSingle'] ?? true );
		$metadata   = [];

		if ( $attributes['isSingle'] ?? true ) {
			$meta_value = [ $meta_value ];
		}

		$meta_value = apply_filters(
			'amnesty_core_post_meta_meta_value',
			$meta_value,
			$post_id,
			$block->context['postType'],
			$attributes['metaKey'],
		);

		if ( $attributes['isLink'] ?? false ) {
			foreach ( $meta_value as $row ) {
				$metadata[] = sprintf( '<a href="%1s">%2s</a>', get_the_permalink( $post_id ), $row );
			}
		} else {
			$metadata = $meta_value;
		}

		$metadata = implode( ', ', $metadata );

		return sprintf(
			'<div %1$s>%2$s</div>',
			$wrapper_attributes,
			$metadata,
		);
	}
}
