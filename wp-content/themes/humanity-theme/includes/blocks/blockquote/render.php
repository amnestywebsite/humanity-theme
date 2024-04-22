<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_blockquote_block' ) ) {
	/**
	 * Render a blockquote
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 *
	 * @return string
	 */
	function render_blockquote_block( array $attributes = [] ): string {
		$attrs = wp_parse_args(
			$attributes,
			[
				'align'      => '',
				'size'       => '',
				'colour'     => '',
				'capitalise' => false,
				'lined'      => true,
				'content'    => '',
				'citation'   => '',
			] 
		);

		$classes = classnames(
			'blockquote',
			[
				"align-{$attrs['align']}" => (bool) $attrs['align'],
				"is-{$attrs['size']}"     => (bool) $attrs['size'],
				"is-{$attrs['colour']}"   => (bool) $attrs['colour'],
				'is-capitalised'          => (bool) $attrs['capitalise'],
				'is-lined'                => (bool) $attrs['lined'],
			] 
		);

		$output = sprintf( '<blockquote class="%s">', esc_attr( $classes ) );

		if ( $attrs['content'] ) {
			$output .= wp_kses_post( wpautop( $attrs['content'] ) );
		}

		if ( $attrs['citation'] ) {
			$output .= sprintf( '<cite>%s</cite>', esc_html( wp_strip_all_tags( $attrs['citation'] ) ) );
		}

		$output .= '</blockquote>';

		return $output;
	}
}
