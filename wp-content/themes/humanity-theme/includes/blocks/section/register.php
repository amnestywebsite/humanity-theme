<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_section_block' ) ) {
	/**
	 * Register the Section block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_section_block(): void {
		register_block_type(
			'amnesty-core/block-section',
			[
				'render_callback' => '\Amnesty\Blocks\amnesty_render_section_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'background'               => [
						'type' => 'string',
					],
					'backgroundImage'          => [
						'type'    => 'string',
						'default' => '',
					],
					'backgroundImageHeight'    => [
						'type'    => 'number',
						'default' => 0,
					],
					'backgroundImageId'        => [
						'type'    => 'number',
						'default' => 0,
					],
					'backgroundImageOrigin'    => [
						'type'    => 'string',
						'default' => '',
					],
					'enableBackgroundGradient' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'hideImageCaption'         => [
						'type'    => 'boolean',
						'default' => true,
					],
					'hideImageCopyright'       => [
						'type'    => 'boolean',
						'default' => false,
					],
					'minHeight'                => [
						'type'    => 'number',
						'default' => 0,
					],
					'padding'                  => [
						'type' => 'string',
					],
					'sectionId'                => [
						'type' => 'string',
					],
					'sectionName'              => [
						'type' => 'string',
					],
					'textColour'               => [
						'type'    => 'string',
						'default' => 'black',
					],
				],
			] 
		);
	}
}
