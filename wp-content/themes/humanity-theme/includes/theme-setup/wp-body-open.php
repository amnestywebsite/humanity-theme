<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_overlay' ) ) {
	/**
	 * Render overlay div for masking the body when modals are open
	 *
	 * @return void
	 */
	function amnesty_overlay(): void {
		echo '<div class="overlay" aria-hidden="true"></div>';
	}
}

add_action( 'wp_body_open', 'amnesty_overlay', 1 );
