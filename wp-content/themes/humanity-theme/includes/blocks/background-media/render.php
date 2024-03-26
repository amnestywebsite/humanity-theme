<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_background_media_column' ) ) {
	/**
	 * Render a background media inner column block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 * @param mixed $content    the block content
	 *
	 * @return string
	 */
	function render_background_media_column( array $attributes, $content = '' ): string {
		if ( empty( $attributes['uniqId'] ) ) {
			$attributes['uniqId'] = amnesty_rand_str( 4 );
		}

		$classes = classnames(
			'text-media--itemContainer',
			[
				"align{$attributes['horizontalAlignment']}" => (bool) $attributes['horizontalAlignment'],
				"is-vertically-aligned-{$attributes['verticalAlignment']}" => (bool) $attributes['verticalAlignment'],
				"has-{$attributes['background']}-background-color" => (bool) $attributes['background'],
			] 
		);

		if ( 0 === absint( $attributes['image'] ) ) {
			$markup  = sprintf( '<div id="%s" class="%s">', esc_attr( $attributes['uniqId'] ), esc_attr( $classes ) );
			$markup .= $content;
			$markup .= '</div>';

			return $markup;
		}

		$opacity  = round( 1 - floatval( $attributes['opacity'] ), 2 );
		$x_offset = round( floatval( $attributes['focalPoint']['x'] ) * 100, 2 );
		$y_offset = round( floatval( $attributes['focalPoint']['y'] ) * 100, 2 );
		$gradient = '';

		if ( 0.0 !== $opacity ) {
			$gradient = sprintf( 'linear-gradient(rgba(255,255,255,%1$f),rgba(255,255,255,%1$f)),', $opacity );
		}

		$image_small = wp_get_attachment_image_url( absint( $attributes['image'] ), 'lwi-block-sm@2x' );
		$image_large = wp_get_attachment_image_url( absint( $attributes['image'] ), 'lwi-block-lg@2x' );

		$css  = sprintf( '#%1$s{background-position:%2$f%% %3$f%%}', esc_attr( $attributes['uniqId'] ), $x_offset, $y_offset );
		$css .= sprintf( '#%1$s{background-image:%2$surl("%3$s")}', esc_attr( $attributes['uniqId'] ), $gradient, esc_url( $image_small ) );
		$css .= sprintf( '@media(min-width:1440px){#%1$s{background-image:%2$surl("%3$s")}}', esc_attr( $attributes['uniqId'] ), $gradient, esc_url( $image_large ) );

		$markup  = sprintf( '<style class="aiic-ignore">%s</style>', $css );
		$markup .= sprintf( '<div id="%s" class="%s">', esc_attr( $attributes['uniqId'] ), esc_attr( $classes ) );
		$markup .= $content;
		$markup .= '</div>';

		return $markup;
	}
}
