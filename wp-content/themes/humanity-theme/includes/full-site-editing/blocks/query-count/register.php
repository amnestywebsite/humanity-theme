<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_query_count_block' ) ) {
	/**
	 * Register the Query Count block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_query_count_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_query_count_block',
			],
		);
	}
}
