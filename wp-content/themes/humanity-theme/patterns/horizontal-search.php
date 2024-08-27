<?php

/**
 * Title: Horizontal search pattern
 * Description: Horizontal search pattern for the theme
 * Slug: amnesty/horizontal-search
 * Inserter: no
 */

$years  = range( gmdate( 'Y' ), 1961 );
$months = amnesty_get_months();

$year_options  = [ __( 'Year', 'amnesty' ) ] + array_combine( $years, $years );
$month_options = [ __( 'Month', 'amnesty' ) ] + $months;

$year_param = '';
if ( get_query_var( 'qyear' ) ) {
	$year_param = absint( get_query_var( 'qyear' ) );
}

$month_param = '';
if ( get_query_var( 'qmonth' ) ) {
	$month_param = absint( get_query_var( 'qmonth' ) );
}

$search_url = current_url();
if ( get_query_var( 'paged' ) ) {
	$search_url = html_entity_decode( get_pagenum_link() );
}

?>

<!-- wp:group {"tagName":"form","className":"wp-block-group wp-block-search horizontal-search"} -->
<form class="wp-block-group wp-block-search horizontal-search" action="<?php echo esc_url( $search_url ); ?>">
<!-- amnesty-core/block-section {"className":"section section--small section--dark postlist-categoriesContainer"} -->
<!-- wp:group {"tagName":"div","className":"wp-block-group container initial-filters"} -->
<div class="wp-block-group container initial-filters">
<!-- wp:group {"tagName":"div","className":"wp-block-group default-search-filters taxonomyArchive-filters"} -->
<div class="wp-block-group default-search-filters taxonomyArchive-filters">
<!-- wp:group {"tagName":"div","className":"wp-block-group search-input"} -->
<div class="wp-block-group search-input">
<!-- wp:pattern {"slug":"amnesty/form-input"} -->
<!-- wp:group {"tagName":"button","className":"wp-block-group btn search-button"} -->
<button class="wp-block-group btn search-button" type="submit">
<!-- wp:group {"tagName":"span","className":"wp-block-group search-button-text"} -->
<span class="wp-block-group search-button-text"><?php /* translators: [front] */ esc_html_e( 'Search', 'amnesty' ); ?></span>
<!-- /wp:group -->
<!-- wp:group {"tagName":"i","className":"wp-block-group icon icon-search"} -->
<i class="wp-block-search icon icon-search"></i>
<!-- /wp:group -->
</button>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"div","className":"wp-block-group basic-filters"} -->
<div class="wp-block-group basic-filters">
<?php

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
</div>
<!-- /wp:group -->

<!-- wp:group {"tagName":"span","className":"wp-block-group btn toggle-search-filters"} -->
<span class="wp-block-group btn toggle-search-filters">
<!-- wp:group {"tagName":"span","className":"wp-block-group toggle-search-filters-text"} -->
<span class="wp-block-group toggle-search-filters-text">
<?php /* translators: [front] */ esc_html_e( 'Filters', 'amnesty' ); ?>
</span>
<!-- /wp:group -->
<!-- wp:group {"tagName":"span","className":"wp-block-group icon icon-arrow-down"} -->
<i class="wp-block-group icon icon-arrow-down"></i>
<!-- /wp:group -->
</span>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"tagName":"div","className":"wp-block-group container additional-filters"} -->
<div class="wp-block-group container additional-filters">
<!-- wp:pattern {"slug":"amnesty/search-filters"} -->
</div>
<!-- /wp:group -->
<!-- /wp:group -->
<!-- /amnesty-core/block-section -->
</form>
<!-- /wp:group -->
<!-- wp:pattern {"slug":"amnesty/filters-active"} -->
