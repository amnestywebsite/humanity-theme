<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_search_header_block' ) ) {
	/**
	 * Register the search header block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_search_header_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_search_header_block',
			],
		);
	}
}
