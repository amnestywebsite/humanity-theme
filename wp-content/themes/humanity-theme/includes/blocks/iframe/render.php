<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_iframe_block' ) ) {
	/**
	 * Render a responsive iframe
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_iframe_block( array $attributes = [] ): string {
		$atts = wp_parse_args(
			$attributes,
			[
				'embedUrl'  => false,
				'width'     => false,
				'height'    => false,
				'minHeight' => false,
				'alignment' => false,
				'title'     => '',
			] 
		);

		$embed_url = $atts['embedUrl'];

		if ( ! $embed_url ) {
			return '';
		}

		$width      = $atts['width'];
		$height     = $atts['height'];
		$min_height = $atts['minHeight'];

		$style = '';

		if ( $width && $height ) {
			$ratio  = $height / $width * 100;
			$ratio  = "{$ratio}%";
			$style .= sprintf( 'padding-top: %s;', $ratio );
		}

		if ( ! $width && ! $height && ! $min_height ) {
			$min_height = 350;
		}

		if ( $min_height ) {
			$style .= sprintf( 'min-height: %dpx;', $min_height );
		}

		spaceless();
		require realpath( __DIR__ . '/views/responsive-iframe.php' );
		return endspaceless( false );
	}
}
