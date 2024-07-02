<?php

declare( strict_types = 1 );

if ( ! function_exists( 'current_url' ) ) {
	/**
	 * Retrieve the current URL
	 *
	 * @package Amnesty
	 *
	 * @return string|null
	 */
	function current_url() {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return null;
		}

		$link = filter_var( $_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL );
		$path = wp_parse_url( $link, PHP_URL_PATH );

		$query = wp_parse_url( $link, PHP_URL_QUERY ) ?: '';
		$query = query_string_to_array( $query );

		if ( ! is_multisite() ) {
			$home = home_url( $path, 'https' );

			return empty( $query ) ? $home : add_query_arg( $query, $home );
		}

		$meta = rtrim( get_network_option( null, 'siteurl' ), '/' );
		$link = $meta . $path;

		return wp_validate_redirect( empty( $query ) ? $link : add_query_arg( $query, $link ) );
	}
}

if ( ! function_exists( 'get_site_language_code' ) ) {
	/**
	 * Retrieve the current site's ISO 639-1 language code
	 *
	 * @package Amnesty
	 *
	 * @param int|null $blog_id the site to get the langauge code for
	 *
	 * @return string
	 */
	function get_site_language_code( int $blog_id = null ): string {
		if ( is_multisite() && $blog_id ) {
			switch_to_blog( $blog_id );
		}

		$lang = get_option( 'WPLANG' ) ?: 'en_US';

		if ( true === apply_filters( 'amnesty_strip_locale_country_code', true ) ) {
			$lang = preg_replace( '/^([a-z]{2,})[-_][a-zA-Z]{2,}$/', '$1', $lang );
		}

		if ( is_multisite() && $blog_id ) {
			restore_current_blog();
		}

		return $lang;
	}
}

if ( ! function_exists( 'get_site_language_name' ) ) {
	/**
	 * Retrieve the current site's language name
	 *
	 * @package Amnesty
	 *
	 * @param int|null $blog_id the site to get the language name for
	 *
	 * @return string
	 */
	function get_site_language_name( int $blog_id = null ): string {
		$override = get_blog_option( $blog_id, 'amnesty_language_name' );

		if ( $override ) {
			return $override;
		}

		$lang = get_blog_option( $blog_id, 'WPLANG' );
		$lang = $lang ?: get_site_option( 'WPLANG' );
		$lang = $lang ?: ( $GLOBALS['wp_local_package'] ?? false );
		$lang = $lang ?: 'en_GB';

		if ( class_exists( 'Locale' ) ) {
			$lang = Locale::getDisplayName( $lang, $lang );
		}

		return strip_language_name_parentheticals( $lang );
	}
}

if ( ! function_exists( 'strip_language_name_parentheticals' ) ) {
	/**
	 * Strips locale names from language names
	 *
	 * @param string $language the language name
	 *
	 * @return string
	 */
	function strip_language_name_parentheticals( string $language ): string {
		if ( false === apply_filters( 'amnesty_strip_locale_country', true ) ) {
			return $language;
		}

		$stripped = preg_replace( '/\s*?([(（]).*?([)）])\s*?/ui', '', $language );
		return $stripped ?: '';
	}
}

if ( ! function_exists( 'do_404' ) ) {
	/**
	 * Throw a 404
	 *
	 * @package Amnesty
	 *
	 * @param bool $nocache whether to send no-cache headers
	 *
	 * @return void
	 */
	function do_404( bool $nocache = true ): void {
		$GLOBALS['wp_query']->set_404();
		status_header( 404 );

		if ( $nocache ) {
			nocache_headers();
		}

		include_once get_404_template();
		die;
	}
}
