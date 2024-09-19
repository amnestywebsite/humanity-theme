<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_search_header_block' ) ) {
	/**
	 * Render the post search header block
	 *
	 * @return string
	 */
	function render_search_header_block(): string {
		spaceless();

		echo 'hiya'; // temp whilst merging upstream

		return endspaceless( false );
	}
}
