<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_links_with_icons_block' ) ) {
	/**
	 * Register the links with icons block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_links_with_icons_block(): void {
		register_block_type(
			'amnesty-core/repeatable-block',
			[
				'render_callback' => 'render_links_with_icons_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'quantity' => [
						'type'    => 'number',
						'default' => 2,
					],
				],
			]
		);
	}
}
