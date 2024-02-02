<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_custom_card_block' ) ) {
	/**
	 * Register the Custom Card block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_custom_card_block(): void {
		register_block_type(
			'amnesty-core/custom-card',
			[
				'render_callback' => 'render_custom_card_block',
				'attributes'      => [
					'centred'       => [
						'type' => 'boolean',
					],
					'content'       => [
						'type' => 'string',
					],
					'imageAlt'      => [
						'type' => 'string',
					],
					'imageID'       => [
						'type' => 'integer',
					],
					'imageURL'      => [
						'type' => 'string',
					],
					'label'         => [
						'type' => 'string',
					],
					'largeImageURL' => [
						'type' => 'string',
					],
					'link'          => [
						'type' => 'string',
					],
					'linkText'      => [
						'type' => 'string',
					],
					'scrollLink'    => [
						'type' => 'string',
					],
					'style'         => [
						'type' => 'string',
					],
				],
			] 
		);
	}
}
