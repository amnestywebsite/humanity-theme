<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_slider_block' ) ) {
	/**
	 * Register the Slider block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_slider_block(): void {
		register_block_type(
			'amnesty-core/block-slider',
			[
				'render_callback' => 'amnesty_render_block_slider',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'sliderId'             => [
						'type' => 'string',
					],
					'slides'               => [
						'type'    => 'array',
						'default' => [],
					],
					'hasArrows'            => [
						'type'    => 'boolean',
						'default' => true,
					],
					'showTabs'             => [
						'type'    => 'boolean',
						'default' => true,
					],
					'hasContent'           => [
						'type'    => 'boolean',
						'default' => true,
					],
					'sliderTitle'          => [
						'type'    => 'string',
						'default' => '',
					],
					'timelineCaptionStyle' => [
						'type'    => 'string',
						'default' => 'dark',
					],
				],
			] 
		);
	}
}
