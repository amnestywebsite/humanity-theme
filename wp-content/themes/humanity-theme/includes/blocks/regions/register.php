<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_regions_block' ) ) {
	/**
	 * Register the Regions block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_regions_block(): void {
		register_block_type(
			'amnesty-core/regions',
			[
				'render_callback' => 'amnesty_render_regions_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			] 
		);
	}
}
