<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_flourish_embed' ) ) {
	/**
	 * Render the Flourish embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_flourish_embed( array $attributes ): string {
		if ( empty( $attributes['source'] ) ) {
			return '';
		}

		wp_enqueue_script( 'flourish-embed' );

		return sprintf( '<div class="flourish-embed" data-src="%s"></div>', esc_attr( $attributes['source'] ) );
	}
}
