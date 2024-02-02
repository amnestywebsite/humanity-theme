<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_link_group_block' ) ) {
	/**
	 * Register the Amnesty Link Group block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_link_group_block(): void {
		register_block_type(
			'amnesty-core/link-group',
			[
				'render_callback' => 'render_link_group_block',
				'attributes'      => [
					'align' => [
						'type'    => 'string',
						'default' => '',
					],
					'title' => [
						'type'    => 'string',
						'default' => '',
					],
					'items' => [
						'type'    => 'array',
						'default' => [],
					],
				],
			] 
		);
	}
}
