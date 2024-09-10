<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_post_meta_block' ) ) {
	/**
	 * Register the Post Meta block for use in the query loop
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_post_meta_block(): void {
		register_block_type(
			'amnesty-core/post-meta',
			[
				'render_callback' => 'render_post_meta_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'textAlign'   => [
						'type'    => 'string',
						'default' => '',
					],
					'format'      => [
						'type'    => 'string',
						'default' => '',
					],
					'isLink'      => [
						'type'    => 'boolean',
						'default' => false,
					],
					'displayType' => [
						'type'    => 'string',
						'default' => false,
					],
					'metaKey'     => [
						'type'    => 'string',
						'default' => '',
					],
					'isSingle'    => [
						'type'    => 'boolean',
						'default' => true,
					],
				],
			]
		);
	}
}
