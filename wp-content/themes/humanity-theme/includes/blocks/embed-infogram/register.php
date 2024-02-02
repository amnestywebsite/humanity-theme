<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_infogram_embed_block' ) ) {
	/**
	 * Register the Infogram Embed block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_infogram_embed_block(): void {
		register_block_type(
			'amnesty-core/embed-infogram',
			[
				'render_callback' => 'amnesty_render_infogram_embed',
				'attributes'      => [
					'identifier' => [
						'type'    => 'string',
						'default' => '',
					],
					'type'       => [
						'type'    => 'string',
						'default' => 'interactive',
					],
					'title'      => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
