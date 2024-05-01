<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_pop_in_block' ) ) {
	/**
	 * Register the pop-in block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_pop_in_block(): void {
		if ( ! amnesty_feature_is_enabled( 'pop-in' ) ) {
			return;
		}

		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_pop_in_block',
			],
		);
	}
}
