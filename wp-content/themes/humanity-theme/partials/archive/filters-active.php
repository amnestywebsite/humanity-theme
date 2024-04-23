<?php

/**
 * Archives partial, active taxonomy filters
 *
 * @package Amnesty\Partials
 */

$taxonomies = array_filter( get_object_taxonomies( 'post', 'objects' ), 'amnesty_filter_object_taxonomies_callback' );

$has_active_taxonomy_filters = false;

if ( $taxonomies ) {
	$has_active_taxonomy_filters = map_array_to_boolean(
		$taxonomies,
		fn ( WP_Taxonomy $tax ): bool => (bool) amnesty_get_query_var( "q{$tax->name}" )
	);
}

$has_other_active_filters = false;
$query_vars_for_filters   = apply_filters( 'amnesty_archive_filter_query_vars', [ 'qyear', 'qmonth' ] );
$active_filter_query_vars = [];

foreach ( $query_vars_for_filters as $filter_var ) {
	$filter_value = amnesty_get_query_var( $filter_var );

	if ( ! $filter_value ) {
		continue;
	}

	$active_filter_query_vars[ $filter_var ] = apply_filters(
		'amnesty_archive_filter_query_var_value',
		sanitize_key( $filter_value ),
		$filter_var,
	);
}

if ( ! $has_active_taxonomy_filters && ! count( $active_filter_query_vars ) ) {
	return;
}

?>

<section class="section section--small filter-wrapper">
	<h4 class="filter-label"><?php /* translators: [front] https://www.amnesty.org/en/latest/?qlocation=1698,1713 */ esc_html_e( 'Filters applied', 'amnesty' ); ?></h4>

	<ul class="active-filters">
	<?php

	foreach ( $taxonomies as $tax_item ) {
		$data  = query_var_to_array( "q{$tax_item->name}" );
		$terms = get_terms_from_query_var( "q{$tax_item->name}", $tax_item->name );

		foreach ( $terms as $_term ) {
			$_link = remove_query_arg( "q{$tax_item->name}" );
			$carry = array_filter( $data, fn ( $v ) => absint( $v ) !== $_term->term_id );

			if ( ! empty( $carry ) ) {
				$_link = add_query_arg( "q{$tax_item->name}", implode( ',', $carry ) );
			}

			printf( '<li><a class="clear-filter" href="%s">%s</a></li>', esc_url( $_link ), esc_html( $_term->name ) );
		}
	}

	foreach ( $active_filter_query_vars as $filter_var => $filter_value ) {
		$_link = remove_query_arg( $filter_var );

		if ( 'qyear' === $filter_var ) {
			$_link = remove_query_arg( 'qmonth', $_link );
		}

		printf( '<li><a class="clear-filter" href="%s">%s</a></li>', esc_url( $_link ), esc_html( $filter_value ) );
	}

	?>

		<li>
			<a class="clear-filter clear-filter--all" href="<?php echo esc_url( strip_query_string( current_url() ) ); ?>">
				<?php /* translators: [front] https://www.amnesty.org/en/latest/?qlocation=1698,1713 */ esc_html_e( 'Clear all', 'amnesty' ); ?>
			</a>
		</li>
	</ul>
</section>
