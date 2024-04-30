<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_links_with_icons_block' ) ) {
	/**
	 * Render a links with icons block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array  $attrs the block attributes
	 * @param string $content the block content
	 *
	 * @return string
	 */
	function render_links_with_icons_block( array $attrs = [], string $content = '' ): string {
		return sprintf(
			'%1$s',
			wp_kses_post( $content )
		);
	}
}
