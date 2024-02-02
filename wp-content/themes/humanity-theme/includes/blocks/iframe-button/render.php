<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_iframe_button_block' ) ) {
	/**
	 * Render the Iframe Button block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 *
	 * @return string
	 */
	function render_iframe_button_block( array $attributes = [] ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'alignment'    => 'none',
				/* translators: [front] AM I think this is used on the petition index or petition block */
				'buttonText'   => __( 'Act Now', 'amnesty' ),
				'className'    => '',
				'iframeHeight' => 760,
				'iframeUrl'    => '',
				'title'        => '',
			] 
		);

		if ( ! esc_url( $attributes['iframeUrl'] ) ) {
			return '';
		}

		$alignment = '';

		if ( 'none' !== $attributes['alignment'] ) {
			$alignment = sprintf( 'is-%s-aligned', $attributes['alignment'] );
		}

		$markup  = sprintf( '<div class="iframeButton-wrapper %s">', esc_attr( $alignment ) );
		$markup .= sprintf( '<button class="iframeButton btn %s">%s</button>', esc_attr( $attributes['className'] ), esc_html( $attributes['buttonText'] ) );
		$markup .= sprintf(
			'<iframe frameborder="0" src="%s" title="%s" height="%d"></iframe>',
			esc_url( $attributes['iframeUrl'] ),
			esc_attr( $attributes['title'] ),
			esc_attr( $attributes['iframeHeight'] ),
		);
		$markup .= '</div>';

		return $markup;
	}
}
