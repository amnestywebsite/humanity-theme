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
					'hideImageCaption' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'hideImageCredit'  => [
						'type'    => 'boolean',
						'default' => false,
					],
					'title'            => [
						'type'    => 'string',
						'default' => '',
					],
					'content'          => [
						'type'    => 'string',
						'default' => '',
					],
					'ctaText'          => [
						'type'    => 'string',
						'default' => '',
					],
					'ctaLink'          => [
						'type'    => 'string',
						'default' => '',
					],
					'alignment'        => [
						'type'    => 'string',
						'default' => '',
					],
					'background'       => [
						'type'    => 'string',
						'default' => '',
					],
					'type'             => [
						'type'    => 'string',
						'default' => '',
					],
				],
			]
		);
	}
}
