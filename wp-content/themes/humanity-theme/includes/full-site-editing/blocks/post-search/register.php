<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_post_search_block' ) ) {
	/**
	 * Register the post search block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_post_search_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_post_search_block',
			],
		);
	}
}
