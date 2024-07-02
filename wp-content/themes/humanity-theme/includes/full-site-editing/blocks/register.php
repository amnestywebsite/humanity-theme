<?php

declare( strict_types = 1 );

require_once realpath( __DIR__ ) . '/pop-in/register.php';
require_once realpath( __DIR__ ) . '/pop-in/render.php';
require_once realpath( __DIR__ ) . '/site-header/register.php';
require_once realpath( __DIR__ ) . '/site-header/render.php';


if ( ! function_exists( 'amnesty_register_full_site_editing_blocks' ) ) {
	/**
	 * Register FSE blocks
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function amnesty_register_full_site_editing_blocks(): void {
		register_pop_in_block();
		register_site_header_block();
	}
}


add_action( 'init', 'amnesty_register_full_site_editing_blocks' );
