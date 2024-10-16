<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_sidebar_block' ) ) {
	/**
	 * Register the sidebar block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_sidebar_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_sidebar_block',
			],
		);
	}
}
