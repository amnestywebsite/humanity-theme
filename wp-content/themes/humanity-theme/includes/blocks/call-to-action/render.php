<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_cta_block' ) ) {
	/**
	 * Render the call to action block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array  $attrs	  the block attributes
	 * @param string $content the block content
	 *
	 * @return string
	 */
	function amnesty_render_cta_block( array $attrs, string $content = '' ): string {
		return wp_kses_post( $content );
	}
}
