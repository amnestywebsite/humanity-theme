<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_cta_block' ) ) {
	/**
	 * Register the Download block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_cta_block(): void {
		register_block_type(
			'amnesty-core/block-call-to-action',
			[
				'render_callback' => 'amnesty_render_cta_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			]
		);
	}
}
