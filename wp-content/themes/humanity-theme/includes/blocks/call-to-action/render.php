<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_cta_block' ) ) {
	/**
	 * Render the call to action block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array  $attrs   the block attributes
	 * @param string $content the block content
	 *
	 * @return string
	 */
	function amnesty_render_cta_block( array $attrs, string $content = '' ): string {
		if ( false !== strpos( $content, 'class="callToAction' ) ) {
			return $content;
		}

		$attrs = wp_parse_args(
			$attrs,
			[
				'preheading' => '',
				'title'      => '',
				'content'    => '',
				'background' => '',
			]
		);

		$pre_heading = $attrs['preheading'];
		$heading     = $attrs['title'];
		$cta_content = $attrs['content'];

		// Set the classes
		$classes = classnames(
			'callToAction',
			[
				"callToAction--{$attrs['background']}" => (bool) $attrs['background'],
			]
		);

		return sprintf(
			'<div class="%1$s" role="note" aria-label="%2$s">
				<h2 class="callToAction-preHeading">%3$s</h2>
				<h1 class="callToAction-heading">%4$s</h1>
				<p class="callToAction-content">%5$s</p>
        		<div className="innerBlocksContainer">
					%6$s
        		</div>
      		</div>',
			esc_attr( $classes ),
			esc_attr( $heading ),
			wp_kses_post( $pre_heading ),
			wp_kses_post( $heading ),
			wp_kses_post( $cta_content ),
			wp_kses_post( $content ),
		);
	}
}
