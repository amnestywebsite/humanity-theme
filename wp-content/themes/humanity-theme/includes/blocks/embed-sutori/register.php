<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_sutori_embed_block' ) ) {
	/**
	 * Register the Sutori Embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_sutori_embed_block(): void {
		register_block_type(
			'amnesty-core/embed-sutori',
			[
				'render_callback' => 'amnesty_render_sutori_embed',
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
