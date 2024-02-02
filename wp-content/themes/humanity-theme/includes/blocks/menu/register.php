<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_menu_block' ) ) {
	/**
	 * Register the Menu block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_menu_block(): void {
		register_block_type(
			'amnesty-core/block-menu',
			[
				'render_callback' => 'amnesty_render_menu_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			] 
		);
	}
}
