<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_related_content_block' ) ) {
	/**
	 * Render the Related Content block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return string
	 */
	function amnesty_render_related_content_block(): string {
		if ( ! class_exists( '\Amnesty\Related_Content' ) ) {
			return '';
		}

		$related_content = new \Amnesty\Related_Content( false );

		return $related_content->get_rendered();
	}
}
