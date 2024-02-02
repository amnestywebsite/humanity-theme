<?php

/**
 * Archives partial, subcategories
 *
 * @package Amnesty\Partials
 */

$sub_categories   = [];
$current_category = false;
$parent_url       = current_url();

if ( is_category() ) {
	$current_category = get_queried_object();
	$parent_url       = amnesty_term_link( get_term_parent( $current_category ) );
	$sub_categories   = get_terms(
		[
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'parent'     => get_term_top_most_parent( $current_category->term_id )->term_id,
		]
	);
}

if ( ! count( $sub_categories ) ) {
	return;
}

$active_subcat = false;

$options = [
	// translators: [front]
	$parent_url => esc_html__( 'Second level category', 'amnesty' ),
];

foreach ( $sub_categories as $sub_cat ) {
	$options[ amnesty_term_link( $sub_cat ) ] = $sub_cat->name;

	if ( $current_category?->term_id === $sub_cat->term_id ) {
		$active_subcat = $sub_cat->name;
		continue;
	}

	if ( ! has_term_parent( $current_category ) ) {
		continue;
	}

	if ( get_term_parent( $current_category )->term_id === $sub_cat->term_id ) {
		$active_subcat = $sub_cat->name;
	}
}

?>

<aside class="news-sidebar section section--small" role="complementary" aria-label="<?php /* translators: [front] */ esc_attr_e( 'List of subcategories', 'amnesty' ); ?>">
<?php

amnesty_render_custom_select(
	[
		// translators: [front]
		'label'      => __( 'Second level category', 'amnesty' ),
		'show_label' => false,
		'is_nav'     => true,
		'type'       => 'nav',
		'options'    => $options,
		'active'     => $active_subcat,
	]
);

get_template_part( 'partials/archive/subsubcategories', args: compact( 'sub_categories' ) )

?>
</aside>
