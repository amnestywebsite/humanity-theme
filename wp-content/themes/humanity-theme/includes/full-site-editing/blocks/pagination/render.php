<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_pagination_block' ) ) {
	/**
	 * Render the pagination block
	 *
	 * @return string
	 */
	function render_pagination_block(): string {
		spaceless();
		get_template_part( 'partials/pagination' );

		return endspaceless( false );
	}
}
