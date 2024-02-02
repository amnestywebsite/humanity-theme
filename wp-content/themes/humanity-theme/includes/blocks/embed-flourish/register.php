<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_flourish_embed_block' ) ) {
	/**
	 * Register the Flourish Embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_flourish_embed_block(): void {
		register_block_type(
			'amnesty-core/embed-flourish',
			[
				'render_callback' => 'amnesty_render_flourish_embed',
				'attributes'      => [
					'source' => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
