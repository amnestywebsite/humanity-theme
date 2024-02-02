<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_allow_position_inline_style' ) ) {
	/**
	 * Add "position" to the list of allowed CSS properties for inline styles
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<int,string> $safe the existing list of allowed properties
	 *
	 * @return array<int,string>
	 */
	function amnesty_allow_position_inline_style( array $safe ): array {
		return array_merge( $safe, [ 'position' ] );
	}
}

if ( ! function_exists( 'amnesty_render_tickcounter_embed' ) ) {
	/**
	 * Render the Tickcounter embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_tickcounter_embed( array $attributes ): string {
		if ( empty( $attributes['source'] ) ) {
			return '';
		}

		wp_enqueue_script( 'tickcounter-sdk' );

		add_filter( 'safe_style_css', 'amnesty_allow_position_inline_style' );
		$output = sprintf( '<div class="align%s">%s</div>', esc_attr( $attributes['alignment'] ), wp_kses_post( $attributes['source'] ) );
		remove_filter( 'safe_style_css', 'amnesty_allow_position_inline_style' );

		return $output;
	}
}
