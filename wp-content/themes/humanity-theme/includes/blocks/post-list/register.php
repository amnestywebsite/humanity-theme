<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_list_block' ) ) {
	/**
	 * Register the List block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_list_block(): void {
		register_block_type(
			'amnesty-core/block-list',
			[
				'render_callback' => 'amnesty_render_list_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'displayAuthor'   => [
						'type'    => 'boolean',
						'default' => false,
					],
					'displayPostDate' => [
						'type'    => 'boolean',
						'default' => false,
					],
				],
			] 
		);
	}
}
