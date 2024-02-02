<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_term_list_block' ) ) {
	/**
	 * Register the Term List block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_term_list_block(): void {
		register_block_type(
			'amnesty-core/term-list',
			[
				'render_callback' => 'amnesty_render_term_list_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			]
		);
	}
}
