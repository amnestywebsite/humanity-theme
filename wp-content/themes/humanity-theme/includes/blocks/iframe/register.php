<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_iframe_block' ) ) {
	/**
	 * Register the Iframe block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_iframe_block(): void {
		register_block_type(
			'amnesty-core/block-responsive-iframe',
			[
				'render_callback' => 'amnesty_render_iframe_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			] 
		);
	}
}
