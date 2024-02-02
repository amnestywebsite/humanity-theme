<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_action_block' ) ) {
	/**
	 * Register the Action block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_action_block(): void {
		register_block_type(
			'amnesty-core/action-block',
			[
				'render_callback' => 'render_action_block',
				'attributes'      => [
					'centred'       => [
						'type'    => 'boolean',
						'default' => false,
					],
					'content'       => [
						'type'    => 'string',
						'default' => '',
					],
					'imageAlt'      => [
						'type'    => 'string',
						'default' => '',
					],
					'imageID'       => [
						'type'    => 'number',
						'default' => 0,
					],
					'imageURL'      => [
						'type'    => 'string',
						'default' => '',
					],
					'label'         => [
						'type'    => 'string',
						'default' => '',
					],
					'largeImageURL' => [
						'type'    => 'string',
						'default' => '',
					],
					'link'          => [
						'type'    => 'string',
						'default' => '',
					],
					'linkText'      => [
						'type'    => 'string',
						'default' => '',
					],
					'style'         => [
						'type'    => 'string',
						'default' => 'standard',
					],
				],
			] 
		);
	}
}
