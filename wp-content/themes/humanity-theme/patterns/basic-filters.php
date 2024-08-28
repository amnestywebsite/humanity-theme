<?php

/**
 * Title: Basic filters pattern
 * Description: Basic filters pattern for the theme
 * Slug: amnesty/basic-filters
 * Inserter: no
 */

do_action( 'amnesty_search_before_basic_filters' );

amnesty_render_custom_select(
	[
		'name'    => 'qyear',
		'label'   => __( 'Year', 'amnesty' ),
		'class'   => 'autosubmit for-year',
		'active'  => $year_param,
		'options' => $year_options,
	]
);

amnesty_render_custom_select(
	[
		'name'     => 'qmonth',
		'label'    => __( 'Month', 'amnesty' ),
		'class'    => 'autosubmit for-month' . ( $year_param ? ' is-active' : '' ),
		'active'   => $month_param,
		'options'  => $month_options,
		'disabled' => ! $year_param,
	]
);

do_action( 'amnesty_search_after_basic_filters' );

?>
