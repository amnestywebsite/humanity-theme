<?php

declare( strict_types = 1 );

if ( ! function_exists( 'set_magic_quotes_runtime' ) ) {
	/**
	 * Function set_magic_quotes_runtime was deprecated in
	 * PHP 5.3 and removed in 7.0.
	 * http://php.net/manual/en/function.set-magic-quotes-runtime.php
	 *
	 * This function is required by WordPress RSS Importer plugin,
	 * which will fail if not found.
	 *
	 * @package Amnesty\Compat
	 *
	 * @return bool
	 */
	function set_magic_quotes_runtime() {
		return true;
	}
}
