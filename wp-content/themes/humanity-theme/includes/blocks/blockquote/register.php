<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_blockquote_block' ) ) {
	/**
	 * Register the Blockquote block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_blockquote_block(): void {
		register_block_type(
			'amnesty-core/quote',
			[
				'render_callback' => 'render_blockquote_block',
				'attributes'      => [
					'align'      => [
						'type'    => 'string',
						'default' => '',
					],
					'size'       => [
						'type'    => 'string',
						'default' => '',
					],
					'colour'     => [
						'type'    => 'string',
						'default' => '',
					],
					'capitalise' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'lined'      => [
						'type'    => 'boolean',
						'default' => true,
					],
					'content'    => [
						'type'    => 'string',
						'default' => '',
					],
					'citation'   => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
