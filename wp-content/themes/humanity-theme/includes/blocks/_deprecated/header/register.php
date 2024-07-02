<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_header_block' ) ) {
	/**
	 * Register the Header block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_header_block(): void {
		register_block_type(
			'amnesty-core/block-hero',
			[
				'render_callback' => '\Amnesty\Blocks\amnesty_render_header_block',
			]
		);
	}
}
