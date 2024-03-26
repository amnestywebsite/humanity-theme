<?php

declare( strict_types = 1 );

// phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh

if ( ! function_exists( 'amnesty_validate_boolish' ) ) {
	/**
	 * Validate a variable as a boolean
	 *
	 * @package Amnesty
	 *
	 * @param mixed $variable     the variable to validate
	 * @param mixed $default_value default value to use if $variable empty
	 *
	 * @return bool
	 */
	function amnesty_validate_boolish( $variable = null, $default_value = false ) {
		if ( is_bool( $variable ) ) {
			return $variable;
		}

		return match ( $variable ) {
			'off', 'no', 'false', 'n', '0' => false,
			'on', 'yes', 'true', 'y', '1' => true,
			null => $default_value,
			default => (bool) $variable,
		};
	}
}

if ( ! function_exists( 'query_var_to_array' ) ) {
	/**
	 * Get a query var value as an array
	 *
	 * @package Amnesty
	 *
	 * @param string $variable the query var
	 *
	 * @return array<mixed>
	 */
	function query_var_to_array( string $variable ): array {
		$value = amnesty_get_query_var( $variable );

		if ( ! $value ) {
			return [];
		}

		if ( ! is_array( $value ) ) {
			return intlist( $value );
		}

		return array_map( 'absint', $value );
	}
}

if ( ! function_exists( 'intlist' ) ) {
	/**
	 * Cast a csv string to an array of ints
	 *
	 * @package Amnesty
	 *
	 * @param string $value the string to cast
	 *
	 * @return array <int,int>
	 */
	function intlist( string $value ): array {
		return array_map( 'absint', explode( ',', $value ) );
	}
}
