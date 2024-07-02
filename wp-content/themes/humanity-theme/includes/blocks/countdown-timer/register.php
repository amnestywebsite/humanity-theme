<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_countdown_block' ) ) {
	/**
	 * Register the countdown timer block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_countdown_block(): void {
		register_block_type(
			'amnesty-core/countdown-timer',
			[
				'render_callback' => 'amnesty_render_countdown_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			]
		);
	}
}
