<?php

declare( strict_types = 1 );

if ( ! function_exists( 'array_set' ) ) {
	/**
	 * Set an item into an associative array using dot-notation
	 *
	 * @package Amnesty
	 *
	 * @param array  $the_array The array to modify
	 * @param string $key       The dot-notation key to set
	 * @param mixed  $value     The data to set
	 *
	 * @return array the modified array
	 */
	function array_set( array &$the_array, string $key = '', mixed $value = null ) {
		$key = strtok( $key, '.' );

		while ( false !== $key ) {
			if ( is_numeric( $key ) ) {
				$key = intval( $key, 10 );
			}

			if ( ! isset( $the_array[ $key ] ) ) {
				$the_array[ $key ] = [];
			}

			// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.VariableRedeclaration
			$the_array = &$the_array[ $key ];
			$key       = strtok( '.' );
		}

		// phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		return $the_array = $value;
	}
}

if ( ! function_exists( 'array_get' ) ) {
	/**
	 * Retrieve an item from an associative array using dot-notation
	 *
	 * @package Amnesty
	 *
	 * @param array  $the_array     The array to retrieve from
	 * @param string $key           The key to retrieve
	 * @param mixed  $default_value The default value to return
	 *
	 * @return mixed the found value or the default
	 */
	function array_get( array $the_array, string $key = '', mixed $default_value = null ) {
		$key = strtok( $key, '.' );

		while ( false !== $key ) {
			if ( is_numeric( $key ) ) {
				$key = intval( $key, 10 );
			}

			if ( ! isset( $the_array[ $key ] ) ) {
				return $default_value;
			}

			$the_array = $the_array[ $key ];
			$key       = strtok( '.' );
		}

		return $the_array;
	}
}

if ( ! function_exists( 'omit' ) ) {
	/**
	 * Omit key(s) from an array
	 *
	 * @package Amnesty
	 *
	 * @param array        $the_array The array to manipulate
	 * @param string|array $props     The array key(s) to omit
	 *
	 * @return array
	 */
	function omit( array $the_array, string|array $props ): array {
		return array_diff_key( $the_array, array_flip( (array) $props ) );
	}
}

if ( ! function_exists( 'array_dot' ) ) {
	/**
	 * Convert a multidimensional associative array to a
	 * flat associative array with dot-notation keys
	 *
	 * @package Amnesty
	 *
	 * @param array $arr The array to flatten
	 *
	 * @return array The flattened array
	 */
	function array_dot( array $arr ) {
		$it  = new RecursiveArrayIterator( $arr );
		$it  = new RecursiveIteratorIterator( $it );
		$res = [];

		foreach ( $it as $leaf ) {
			$keys = [];

			foreach ( range( 0, $it->getDepth() ) as $depth ) {
				$keys[] = $it->getSubIterator( $depth )->key();
			}

			$res[ implode( '.', $keys ) ] = $leaf;
		}

		return $res;
	}
}

if ( ! function_exists( 'map_array_to_boolean' ) ) {
	/**
	 * Convert an array to a boolean after applying callback to each element
	 *
	 * @package Amnesty
	 *
	 * @param array<mixed> $the_array The array to reduce
	 * @param callable     $callback  The callback to apply to each element
	 * @param string       $mode      Reduce with OR or AND
	 *
	 * @return bool|null
	 */
	function map_array_to_boolean( array $the_array, callable $callback, string $mode = 'or' ): ?bool {
		if ( ! in_array( strtolower( $mode ), [ 'or', 'and' ], true ) ) {
			return null;
		}

		if ( 'or' === strtolower( $mode ) ) {
			$reducer = fn ( $input, $carry ) => $carry || (bool) $input;
		}

		if ( 'and' === strtolower( $mode ) ) {
			$reducer = fn ( $input, $carry ) => $carry && (bool) $input;
		}

		return array_reduce( array_map( $callback, $the_array ), $reducer, null );
	}
}
