<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_archive_header_block' ) ) {
	/**
	 * Render the post search block
	 *
	 * @return string
	 */
	function render_archive_header_block(): string {
		spaceless();

		get_template_part( 'partials/archive/header' );

		return endspaceless( false );
	}
}
