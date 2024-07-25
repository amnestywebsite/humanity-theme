<?php

if ( ! function_exists( 'register_hero_block' ) ) {
	/**
	 * Register the  Hero block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_hero_block(): void {
		register_block_type(
			'amnesty-core/hero',
			[
				'render_callback' => 'render_hero_block',
				'attributes'      => [
					'align'            => [
						'type'    => 'string',
						'default' => '',
					],
					'background'       => [
						'type'    => 'string',
						'default' => '',
					],
					'content'          => [
						'type'    => 'string',
						'default' => '',
					],
					'ctaLink'          => [
						'type'    => 'string',
						'default' => '',
					],
					'ctaText'          => [
						'type'    => 'string',
						'default' => '',
					],
					'featuredVideoId'  => [
						'type'    => 'integer',
						'default' => 0,
					],
					'hideImageCaption' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'hideImageCredit'  => [
						'type'    => 'boolean',
						'default' => false,
					],
					'imageID'          => [
						'type'    => 'integer',
						'default' => 0,
					],
					'title'            => [
						'type'    => 'string',
						'default' => '',
					],
					'type'             => [
						'type'    => 'string',
						'default' => 'image',
					],
				],
			]
		);
	}
}
