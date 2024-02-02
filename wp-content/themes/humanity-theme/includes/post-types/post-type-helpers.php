<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_post_types' ) ) {
	/**
	 * Get a list of all registered post type objects.
	 *
	 * @see register_post_type() for accepted arguments.
	 *
	 * @package Amnesty\PostTypes
	 *
	 * @param array|string $args     Optional. An array of key => value arguments to match against
	 *                               the post type objects. Default empty array.
	 * @param string       $output   Optional. The type of output to return. Accepts post type 'names'
	 *                               or 'objects'. Default 'names'.
	 * @param string       $operator Optional. The logical operation to perform. 'or' means only one
	 *                               element from the array needs to match; 'and' means all elements
	 *                               must match; 'not' means no elements may match. Default 'and'.
	 * @return string[]|WP_Post_Type[] An array of post type names or objects.
	 */
	function amnesty_get_post_types( array $args = [], string $output = 'names', string $operator = 'and' ): array {
		if ( 'objects' === $output ) {
			return get_post_types( $args, $output, $operator );
		}

		$objects    = get_post_types( $args, 'objects', $operator );
		$post_types = [];

		foreach ( $objects as $wpname => $data ) {
			$name = $data->codename ?? $data->name;

			$post_types[ $name ] = $wpname;
		}

		return $post_types;
	}
}
