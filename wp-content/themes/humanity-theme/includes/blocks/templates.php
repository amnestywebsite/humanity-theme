<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_add_section_to_pages' ) ) {
	/**
	 * When creating a new page, automatically add a section block.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function amnesty_add_section_to_pages() {
		$object = get_post_type_object( 'page' );

		$object->template = [
			[ 'amnesty-core/block-section' ],
		];
	}
}

add_action( 'init', 'amnesty_add_section_to_pages' );
