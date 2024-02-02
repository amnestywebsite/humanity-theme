<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_infogram_embed' ) ) {
	/**
	 * Render the Infogram embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_infogram_embed( array $attributes ): string {
		if ( empty( $attributes['identifier'] ) ) {
			return '';
		}

		$markup = sprintf(
			'<div class="infogram-embed" data-id="%1$s" data-type="%2$s" data-title="%3$s"></div>',
			esc_attr( $attributes['identifier'] ),
			esc_attr( $attributes['type'] ),
			esc_attr( $attributes['title'] )
		);

		$script = "<script>(function(w,i){w[i]&&w[i].initialized&&w[i].process&&w[i].process()}(window,'InfogramEmbeds'))</script>";

		return $markup . $script;
	}
}
