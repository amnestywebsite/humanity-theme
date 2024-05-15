<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_raw_code_block' ) ) {
	/**
	 * Register the Raw Code block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_raw_code_block(): void {
		register_block_type( 'amnesty-core/code' );
	}
}
