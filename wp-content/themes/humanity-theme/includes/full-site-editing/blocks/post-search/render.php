<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_post_search_block' ) ) {
	/**
	 * Render the post search block
	 *
	 * @return string
	 */
	function render_post_search_block(): string {
		spaceless();

		get_template_part( 'partials/post/post-search' );

		return endspaceless( false );
	}
}
