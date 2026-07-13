<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_cache_header' ) ) {
	/**
	 * Add caching header for WP Engine.
	 *
	 * No longer implemented. Caching rules should be added to
	 * WP Engine via their WREN dashboard in the user portal.
	 *
	 * @deprecated v3.0.4
	 * @see \Amnesty\Caching::class
	 *
	 * @return void
	 */
	function amnesty_cache_header(): void {
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__( 'No longer implemented. Add caching headers via the WREN dashboard in the user portal.', 'amnesty' ),
			'v3.0.4',
		);
	}
}
