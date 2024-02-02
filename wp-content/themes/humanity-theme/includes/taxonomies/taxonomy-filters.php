<?php

/**
 * Miscellaneous taxonomy filters
 */

declare( strict_types = 1 );

/**
 * Allow some post types access to the category taxonomy
 */
add_action(
	'init',
	function () {
		register_taxonomy_for_object_type( 'category', 'attachment' );
		register_taxonomy_for_object_type( 'category', 'page' );
	}
);

/**
 * Add currently-requested category to the filter query var list
 */
add_filter(
	'request',
	// this is fired prior to `is_category()` being populated with the correct value
	function ( array $query_vars ): array {
		if ( isset( $query_vars['category_name'] ) ) {
			$id = get_cat_ID( $query_vars['category_name'] );

			$query_vars['qcategory'] = strval( absint( $id ) );
		}

		return $query_vars;
	}
);
