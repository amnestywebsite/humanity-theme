<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_rewrite_author_base' ) ) {
	/**
	 * Remove static front from author URLs
	 *
	 * @package Amnesty\Permalinks
	 *
	 * @global $wp_rewrite
	 *
	 * @return void
	 */
	function amnesty_rewrite_author_base() {
		global $wp_rewrite;

		$wp_rewrite->author_base      = '';
		$wp_rewrite->author_structure = '/author/%author%/';
	}
}

add_action( 'init', 'amnesty_rewrite_author_base' );
