<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_cta_block' ) ) {
	/**
	 * Render a download block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attrs the block attributes
	 *
	 * @return string|null
	 */
	function amnesty_render_cta_block( $attrs, $content) {
		// Apply filters to the attributes
		$attrs = apply_filters( 'amnesty_cta_block_attributes', $attrs );

		// Parse the attributes
		$pre_heading = isset( $attrs['preheading'] ) ? $attrs['preheading'] : '';
		$heading     = isset( $attrs['title'] ) ? $attrs['title'] : '';
		$cta_content     = isset( $attrs['content'] ) ? $attrs['content'] : '';

		// Set the classes
		$classes = classnames(
			'callToAction',
			[
				"callToAction--{$attrs['background']}" => (bool) $attrs['background'],
			]
		);

		return sprintf(
			'<div class="%1$s" role="note" aria-label="%3$s">
				<h2 class="callToAction-preHeading">%2$s</h2>
				<h1 class="callToAction-heading">%3$s</h1>
				<p class="callToAction-content">%4$s</p>
        		<div className="innerBlocksContainer">
					%5$s
        		</div>
      		</div>',
			esc_attr( $classes ),
			wp_kses_post( $pre_heading ),
			wp_kses_post( $heading ),
			wp_kses_post( $cta_content ),
			wp_kses_post( $content )
		);
	}
}
