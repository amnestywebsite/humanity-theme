<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_related_content_block' ) ) {
	/**
	 * Register the Related Content block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_related_content_block(): void {
		register_block_type(
			'amnesty-core/related-content',
			[
				'render_callback' => 'amnesty_render_related_content_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [],
			] 
		);
	}
}
