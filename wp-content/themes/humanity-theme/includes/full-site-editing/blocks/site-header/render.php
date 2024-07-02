<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_site_header_block' ) ) {
	/**
	 * Render the site header block
	 *
	 * @return string
	 */
	function render_site_header_block(): string {
		spaceless();

		amnesty_overlay();
		amnesty_skip_link();
		get_template_part( 'partials/language-selector' );
		get_template_part( 'partials/navigation/desktop' );

		return endspaceless( false );
	}
}
