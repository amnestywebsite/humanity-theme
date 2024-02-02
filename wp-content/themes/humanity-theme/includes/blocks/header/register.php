<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_header_block' ) ) {
	/**
	 * Register the Header block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_header_block(): void {
		register_block_type(
			'amnesty-core/header',
			[
				'render_callback' => '\Amnesty\Blocks\amnesty_render_header_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'hideImageCaption'   => [
						'type'    => 'boolean',
						'default' => true,
					],
					'hideImageCopyright' => [
						'type'    => 'boolean',
						'default' => false,
					],
				],
			] 
		);
	}
}
