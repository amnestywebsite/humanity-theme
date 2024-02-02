<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_iframe_button_block' ) ) {
	/**
	 * Register the Iframe Button block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_iframe_button_block(): void {
		register_block_type(
			'amnesty-core/iframe-button',
			[
				'render_callback' => 'render_iframe_button_block',
				'attributes'      => [
					'iframeUrl'    => [
						'type' => 'string',
					],
					'iframeHeight' => [
						'type'    => 'number',
						'default' => 760,
					],
					'buttonText'   => [
						'type'    => 'string',
						/* translators: [front] */
						'default' => __( 'Act Now', 'amnesty' ),
					],
					'alignment'    => [
						'type'    => 'string',
						'default' => 'none',
					],
					'title'        => [
						'type'    => 'string',
						'default' => '',
					],
				],
			] 
		);
	}
}
