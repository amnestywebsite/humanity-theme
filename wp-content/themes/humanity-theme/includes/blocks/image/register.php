<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_image_block' ) ) {
	/**
	 * Register the Image block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_image_block(): void {
		register_block_type(
			'amnesty-core/image-block',
			[
				'render_callback' => 'render_image_block',
				'attributes'      => [
					'type'       => [
						'type' => 'string',
					],
					'style'      => [
						'type' => 'string',
					],
					'align'      => [
						'type' => 'string',
					],
					'hasOverlay' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'parallax'   => [
						'type'    => 'boolean',
						'default' => false,
					],
					'identifier' => [
						'type'      => 'string',
						'source'    => 'attribute',
						'selector'  => '.imageBlock',
						'attribute' => 'class',
					],
					'imageID'    => [
						'type' => 'integer',
					],
					'imageURL'   => [
						'type' => 'string',
					],
					'videoID'    => [
						'type' => 'integer',
					],
					'videoURL'   => [
						'type' => 'string',
					],
					'title'      => [
						'type' => 'string',
					],
					'content'    => [
						'type' => 'string',
					],
					'buttons'    => [
						'type'    => 'array',
						'default' => [],
					],
					'caption'    => [
						'type' => 'string',
					],
				],
			] 
		);
	}
}
