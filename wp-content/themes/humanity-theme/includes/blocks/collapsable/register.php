<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_collapsable_block' ) ) {
	/**
	 * Register the collapsable block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_collapsable_block(): void {
		register_block_type(
			'amnesty-core/collapsable',
			[
				'render_callback' => 'render_collapsable_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'supports'        => [
					'className' => true,
				],
				'attributes'      => [
					'collapsed' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'title'     => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
