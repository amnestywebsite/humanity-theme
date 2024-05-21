<?php

if ( ! taxonomy_exists( $attributes['taxonomy'] ) ) {
	return;
}

if ( ! function_exists( 'add_child_terms_identifier_to_category_list' ) ) {
	/**
	 * Add a class name to terms with children
	 *
	 * @param array<int,string>   $classes the current tern's class list
	 * @param WP_Term             $term    the current term object
	 * @param int                 $depth   the current iterator depth
	 * @param array<string,mixed> $attributes the current term's attributes
	 */
	function add_child_terms_identifier_to_category_list( array $classes, WP_Term $term, int $depth, array $attributes = [] ): array {
		if ( isset( $attributes['has_children'] ) && $attributes['has_children'] ) {
			$classes[] = 'has-children';
		}

		return $classes;
	}
}

$query_args = [
	'depth'              => absint( $attributes['depth'] ) + 1,
	'hide_empty'         => false,
	'taxonomy'           => $attributes['taxonomy'],
	'title_li'           => false,
	'show_option_none'   => false,
	'use_desc_for_title' => false,
];

if ( $attributes['regionsOnly'] ) {
	// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	$query_args['meta_query'] = [
		[
			'key'     => 'type',
			'value'   => [ 'region', 'subregion' ],
			'compare' => 'IN',
		],
	];
}

$title_id      = sanitize_title_with_dashes( $attributes['title'] );
$title_classes = classnames( [ "is-{$attributes['alignment']}-aligned" => (bool) $attributes['alignment'] ] );
$wrap_classes  = classnames(
	'wp-block-amnesty-core-regions',
	[
		"has-{$attributes['background']}-background-color" => (bool) $attributes['background'],
	]
);

?>
<aside class="<?php echo esc_attr( $wrap_classes ); ?>" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<h2 id="<?php echo esc_attr( $title_id ); ?>" class="<?php echo esc_attr( $title_classes ); ?>"><?php echo esc_html( $attributes['title'] ); ?></h2>
	<ul class="listItems" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b026-regions-list-block/ */ esc_attr_e( 'Hierarchical list of terms', 'amnesty' ); ?>">
	<?php

	add_filter( 'category_css_class', 'add_child_terms_identifier_to_category_list', 10, 4 );
	wp_list_categories( $query_args );
	remove_filter( 'category_css_class', 'add_child_terms_identifier_to_category_list', 10, 4 );

	?>
	</ul>
</aside>
