<?php

$current_category = false;
$parent_url       = current_url();
$active_subcat    = false;

if ( is_category() ) {
	$current_category = get_queried_object();
	$parent_url       = amnesty_term_link( get_term_parent( $current_category ) );
}

foreach ( $args['sub_categories'] as $sub_cat ) {
	if ( ! is_current_category( $sub_cat ) ) {
		continue;
	}

	$sub_sub_cats = get_terms(
		[
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'child_of'   => $sub_cat->term_id,
		]
	);

	if ( ! $sub_sub_cats ) {
		continue;
	}

	$options = [
		// translators: [front]
		$parent_url => __( 'Third level category', 'amnesty' ),
	];

	foreach ( $sub_sub_cats as $sub_sub_cat ) {
		$options[ amnesty_term_link( $sub_sub_cat ) ] = $sub_sub_cat->name;

		if ( $current_category?->term_id === $sub_sub_cat->term_id ) {
			$active_subcat = $sub_sub_cat->name;
			continue;
		}

		if ( ! has_term_parent( $current_category ) ) {
			continue;
		}

		if ( get_term_parent( $current_category )->term_id === $sub_sub_cat->term_id ) {
			$active_subcat = $sub_sub_cat->name;
		}
	}

	amnesty_render_custom_select(
		[
			// translators: [front]
			'label'      => __( 'Third level category', 'amnesty' ),
			'show_label' => false,
			'is_nav'     => true,
			'type'       => 'nav',
			'options'    => $options,
			'active'     => $active_subcat,
		]
	);
}
