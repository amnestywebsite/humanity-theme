<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_site_header_block' ) ) {
	/**
	 * Register the site header block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_site_header_block(): void {
		register_block_type_from_metadata(
			__DIR__,
			[
				'render_callback' => 'render_site_header_block',
			],
		);
	}
}
