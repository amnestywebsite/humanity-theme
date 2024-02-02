<?php

declare( strict_types = 1 );

add_action( 'init', 'nojs', 1 );

if ( ! function_exists( 'nojs' ) ) {
	/**
	 * Outputs the no-js script, hashed for CSP
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function nojs(): void {
		if ( is_admin() ) {
			return;
		}

		$script = "(function(h){h.classList.remove('no-js');h.classList.add('js')})(document.documentElement);";
		add_filter( 'amnesty_csp_script', fn ( $attribute ) => sprintf( "%s 'sha256-%s'", $attribute, base64_encode( hash( 'sha256', $script, true ) ) ) );
		add_action( 'wp_head', fn () => printf( '<script>%s</script>', $script ), 1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
