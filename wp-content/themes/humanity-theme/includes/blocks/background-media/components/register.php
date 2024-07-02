<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_background_media_column_block' ) ) {
	/**
	 * Register the Background Media block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_background_media_column_block(): void {
		register_block_type(
			'amnesty-core/background-media-column',
			[
				'render_callback' => 'render_background_media_column',
				'attributes'      => [
					'uniqId'              => [
						'type'    => 'string',
						'default' => '',
					],
					'horizontalAlignment' => [
						'type'    => 'string',
						'default' => '',
					],
					'verticalAlignment'   => [
						'type'    => 'string',
						'default' => '',
					],
					'image'               => [
						'type'    => 'number',
						'default' => 0,
					],
					'background'          => [
						'type'    => 'string',
						'default' => '',
					],
					'opacity'             => [
						'type'    => 'number',
						'default' => 1,
					],
					'focalPoint'          => [
						'type'    => 'array',
						'default' => [
							'x' => 0.5,
							'y' => 0.5,
						],
					],
				],
			]
		);
	}
}
