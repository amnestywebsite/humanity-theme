<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_cta_inner_blocks' ) ) {
	/**
	 * Render inner blocks
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $inner_blocks the inner blocks
	 *
	 * @return string
	 */
	function render_cta_inner_blocks( $inner_blocks = [] ) {
		if ( ! empty( $inner_blocks ) ) {
			foreach ( $inner_blocks as $inner_block ) {
				return render_block( $inner_block );
			}
		}
	}
}

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
	function amnesty_render_cta_block( $attrs = [] ) {
		// Get the post content
		global $post;

		// Get the blocks
		$blocks = parse_blocks( $post->post_content );

		$inner_blocks = '';

		// Loop through the blocks
		foreach ( $blocks as $block ) {
			// Check if the block is the call to action block
			if ( 'amnesty-core/block-call-to-action' === $block['blockName'] ) {
				// Get the inner blocks
				$inner_blocks = $block['innerBlocks'];
			}
		}

		// Apply filters to the attributes
		$attrs = apply_filters( 'amnesty_cta_block_attributes', $attrs );

		// Parse the attributes
		$pre_heading = isset( $attrs['preheading'] ) ? $attrs['preheading'] : '';
		$heading     = isset( $attrs['title'] ) ? $attrs['title'] : '';
		$content     = isset( $attrs['content'] ) ? $attrs['content'] : '';

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
			wp_kses_post( $content ),
			render_cta_inner_blocks( $inner_blocks ),
		);
	}
}
