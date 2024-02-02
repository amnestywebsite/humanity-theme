<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_fix_archive_pagination' ) ) {
	/**
	 * Shift category archive by year rewrite rule to below
	 * the category archive pagination rule to prevent 404s
	 * on pages greater than 999.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param array<string,string> $rules the list of generated rewrite rules
	 *
	 * @return array<string,string>
	 */
	function amnesty_fix_archive_pagination( array $rules ): array {
		global $wp_rewrite;

		$front = ltrim( $wp_rewrite->front, '/' );
		$rule  = sprintf( '%s(.+?)/([0-9]{4})/?$', $front );

		unset( $rules[ $rule ] );
		$rules[ $rule ] = 'index.php?category_name=$matches[1]&year=$matches[2]';

		return $rules;
	}
}

add_filter( 'rewrite_rules_array', 'amnesty_fix_archive_pagination' );
