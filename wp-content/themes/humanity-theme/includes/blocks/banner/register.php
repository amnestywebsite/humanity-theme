<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_banner_block' ) ) {
	/**
	 * Register the banner block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_banner_block(): void {
		register_block_type(
			'amnesty-core/header',
			[
				'render_callback' => '\Amnesty\Blocks\amnesty_render_header_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			]
		);
	}
}
