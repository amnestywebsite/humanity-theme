<?php

declare( strict_types = 1 );

if ( ! function_exists( 'pascal' ) ) {
	/**
	 * Convert a string to PascalCase
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The string to texturise
	 *
	 * @return string
	 */
	function pascal( string $the_string = '' ) {
		$the_string = preg_replace( '/[\'"]/', '', $the_string );
		$the_string = preg_replace( '/[^a-zA-Z0-9]+/', ' ', $the_string );
		return preg_replace( '/\s+/', '', ucwords( $the_string ) );
	}
}

if ( ! function_exists( 'camel' ) ) {
	/**
	 * Convert a string to camelCase
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The string to texturise
	 *
	 * @return string
	 */
	function camel( string $the_string = '' ) {
		return lcfirst( pascal( $the_string ) );
	}
}

if ( ! function_exists( 'sentence' ) ) {
	/**
	 * Convert a string to sentence case
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The string to texturise
	 *
	 * @return string
	 */
	function sentence( string $the_string = '' ) {
		return preg_replace( '/([a-z])([A-Z])/', '$1 $2', pascal( $the_string ) );
	}
}

if ( ! function_exists( 'format_for_aria_label' ) ) {
	/**
	 * Format a string for use in the aria-label attribute.
	 * Strips tags, whitespace, and normalises text transformations.
	 * This will break poorly-written acronymns, but it fixes uppercase
	 * words being read by some screen readers as acronyms.
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The string to normalise
	 *
	 * @return string
	 */
	function format_for_aria_label( string $the_string = '' ) {
		return ucwords( strtolower( normalize_whitespace( wp_strip_all_tags( $the_string ) ) ) );
	}
}

if ( ! function_exists( 'spaceless' ) ) {
	/**
	 * Begin output buffering
	 *
	 * @package Amnesty
	 *
	 * @param callable|null $callback Output buffer callback
	 *
	 * @return void
	 */
	function spaceless( ?callable $callback = null ) {
		ob_start( $callback );
	}
}

if ( ! function_exists( 'endspaceless' ) ) {
	/**
	 * End output buffering, strip whitespace between HTML tags,
	 * and echo result
	 *
	 * @package Amnesty
	 *
	 * @param bool $output Whether to echo or return
	 *
	 * @return string|void
	 */
	function endspaceless( bool $output = true ) {
		$data = trim( preg_replace( '~>[\b\s]*~', '>', ob_get_clean() ) );

		if ( ! $output ) {
			return $data;
		}

		// phpcs:ignore
		echo $data;
	}
}

if ( ! function_exists( 'aria_selected' ) ) {
	/**
	 * Outputs the html aria-selected attribute.
	 *
	 * Compares the first two arguments and if identical marks as selected
	 *
	 * @package Amnesty
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $selected One of the values to compare
	 * @param mixed $current  The other value to compare if not just true. Default true
	 * @param bool  $output   Whether to echo or just return the string
	 *
	 * @return string html attribute
	 */
	function aria_selected( mixed $selected, mixed $current = true, bool $output = true ) {
		$result = 'aria-selected="false"';

		if ( (string) $selected === (string) $current ) {
			$result = 'aria-selected="true"';
		}

		if ( $output ) {
			// phpcs:ignore
			echo $result;
		}

		return $result;
	}
}

if ( ! function_exists( 'amnesty_rand_str' ) ) {
	/**
	 * Return a cryptographically-random string
	 *
	 * @package Amnesty
	 *
	 * @param int $length The number of bytes to return
	 *
	 * @return string
	 */
	function amnesty_rand_str( int $length = 8 ): string {
		static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$prefix = $chars[ wp_rand( 0, mb_strlen( $chars, 'UTF-8' ) - 1 ) ];
		$random = bin2hex( random_bytes( $length * 2 ) );

		return mb_substr( $prefix . $random, 0, $length, 'UTF-8' );
	}
}

if ( ! function_exists( 'amnesty_hash_id' ) ) {
	/**
	 * Return a HTML id attribute-safe hash
	 *
	 * @package Amnesty
	 *
	 * @param mixed $data The data to hash
	 *
	 * @return string
	 */
	function amnesty_hash_id( mixed $data = '' ): string {
		$hash = md5( (string) $data );

		preg_match( '/[a-zA-z]/', $hash, $index );

		$prefix = is_numeric( $index[0] ) ? 'a' : $index[0];

		return $prefix . $hash;
	}
}

if ( ! function_exists( 'strip_query_string' ) ) {
	/**
	 * Strip a query string from a URI
	 *
	 * @package Amnesty
	 *
	 * @param string $url The URI to strip
	 *
	 * @return string
	 */
	function strip_query_string( string $url ): string {
		$query = wp_parse_url( $url, PHP_URL_QUERY );
		return str_replace( "?{$query}", '', $url );
	}
}

if ( ! function_exists( 'query_string_to_array' ) ) {
	/**
	 * Convert a raw query string to an associative array
	 *
	 * @package Amnesty
	 *
	 * @param string $query The query string
	 *
	 * @return array
	 */
	function query_string_to_array( string $query ): array {
		$query = rawurldecode( (string) $query );
		$query = explode( '&', $query );
		$query = array_map( fn ( string $arg ) => explode( '=', $arg ), $query );
		$query = array_map( fn ( array $arg ) => [ $arg[0] => $arg[1] ?? '' ], $query );
		$query = array_merge( ...$query );

		return (array) $query;
	}
}

if ( ! function_exists( 'remove_arabic_diacritics' ) ) {
	/**
	 * Strip superfluous diacritic marks from Arabic text
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The text to strip
	 *
	 * @return string
	 */
	function remove_arabic_diacritics( string $the_string ): string {
		static $map = [
			// this may need fleshing out.
			"~^\x{0622}~u" => 'ا',
			"~^\x{0623}~u" => 'ا',
			"~^\x{0625}~u" => 'ا',
		];

		foreach ( $map as $from => $to ) {
			$the_string = preg_replace( $from, $to, $the_string );
		}

		return $the_string;
	}
}

if ( ! function_exists( 'remove_arabic_the' ) ) {
	/**
	 * Remove the Arabic word for "the" from the start of a string
	 *
	 * @package Amnesty
	 *
	 * @param string $the_string The string to manipulate
	 *
	 * @return string
	 */
	function remove_arabic_the( string $the_string ): string {
		if ( 0 === mb_strpos( $the_string, 'ا', 0, 'UTF-8' ) ) {
			return mb_substr( $the_string, 2, null, 'UTF-8' );
		}

		return $the_string;
	}
}
