<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_stat_counter_block' ) ) {
	/**
	 * Register Stat Counter block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_stat_counter_block(): void {
		register_block_type(
			'amnesty-core/counter',
			[
				'editor_script'   => 'amnesty-stat-counter-block-editor',
				'render_callback' => 'render_stat_counter_block',
			]
		);
	}
}
