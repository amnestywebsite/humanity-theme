<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_pagination_block' ) ) {
	/**
	 * Register the pagination block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_pagination_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_pagination_block',
			],
		);
	}
}
