<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_download_block' ) ) {
	/**
	 * Register the Download block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_download_block(): void {
		register_block_type(
			'amnesty-core/block-download',
			[
				'render_callback' => 'amnesty_render_download_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			] 
		);
	}
}
