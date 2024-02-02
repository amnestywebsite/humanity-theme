<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_tweet_action_block' ) ) {
	/**
	 * Register the Tweet Action block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_tweet_action_block(): void {
		register_block_type(
			'amnesty-core/tweet-block',
			[
				'render_callback' => 'amnesty_render_tweet_action',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'title'     => [
						'type' => 'string',
					],
					'content'   => [
						'type' => 'string',
					],
					'size'      => [
						'type' => 'string',
					],
					'alignment' => [
						'type' => 'string',
					],
					'embedLink' => [
						'type'    => 'boolean',
						'default' => false,
					],
				],
			] 
		);
	}
}
