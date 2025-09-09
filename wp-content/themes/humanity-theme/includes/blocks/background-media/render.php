<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_background_media_block' ) ) {
	/**
	 * Render the Background Media block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes The block attributes
	 * @param string              $content    The block content
	 *
	 * @return string
	 */
	function render_background_media_block( array $attributes, string $content = '' ): string {
		return sprintf(
			'<div %1$s>%2$s</div>',
			wp_kses_data( get_block_wrapper_attributes() ),
			$content
		);
	}
}
