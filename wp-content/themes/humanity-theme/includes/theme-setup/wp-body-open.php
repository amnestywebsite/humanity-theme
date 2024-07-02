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

if ( ! function_exists( 'amnesty_skip_link' ) ) {
	/**
	 * Render skip to content link
	 *
	 * @return void
	 */
	function amnesty_skip_link(): void {
		printf(
			'<a class="skipLink" href="#main" tabindex="1">%s</a>',
			/* translators: [front] Accessibility label for screen reader/keyboard users */
			esc_html__( 'Skip to main content', 'amnesty' )
		);
	}
}

add_action( 'wp_body_open', 'amnesty_skip_link', 1 );
