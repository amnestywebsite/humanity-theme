<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_tickcounter_embed_block' ) ) {
	/**
	 * Register the Tickcounter Embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_tickcounter_embed_block(): void {
		register_block_type(
			'amnesty-core/embed-tickcounter',
			[
				'render_callback' => 'amnesty_render_tickcounter_embed',
				'attributes'      => [
					'alignment' => [
						'type'    => 'string',
						'default' => 'center',
					],
					'source'    => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
