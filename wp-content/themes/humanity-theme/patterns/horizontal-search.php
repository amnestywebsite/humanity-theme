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
<form class="wp-block-search horizontal-search" action="<?php echo esc_url( $search_url ); ?>">
<!-- amnesty-core/block-section {"className":"section section--small section--dark postlist-categoriesContainer"} -->
<!-- wp:group {"tagName":"div","className":"container initial-filters"} -->
<div class="wp-block-group container initial-filters">
<!-- wp:group {"tagName":"div","className":"default-search-filters taxonomyArchive-filters"} -->
<div class="wp-block-group default-search-filters taxonomyArchive-filters">
<!-- wp:group {"tagName":"div","className":"search-input"} -->
<div class="wp-block-group search-input">
<!-- wp:pattern {"slug":"amnesty/form-input"} /-->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"tagName":"button","type":"submit", "className":"is-style-search"} -->
<div class="wp-block-button is-style-search"><button type="submit" class="wp-block-button__link wp-element-button">
	<span class="search-button-text"><?php /* translators: [front] */ esc_html_e( 'Search', 'amnesty' ); ?></span>
	<i class="wp-block-search icon icon-search"></i>
</button></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
<!-- wp:group {"tagName":"div","className":"basic-filters"} -->
<div class="wp-block-group basic-filters">
<!-- wp:pattern {"slug":"amnesty/basic-filters"} /-->
</div>
<!-- /wp:group -->
<span class="wp-block-group btn toggle-search-filters">
<span class="wp-block-group toggle-search-filters-text">
<?php /* translators: [front] */ esc_html_e( 'Filters', 'amnesty' ); ?>
</span>
<i class="wp-block-group icon icon-arrow-down"></i>
</span>
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"tagName":"div","className":"container additional-filters"} -->
<div class="wp-block-group container additional-filters">
<!-- wp:pattern {"slug":"amnesty/search-filters"} /-->
</div>
<!-- /wp:group -->
<!-- /amnesty-core/block-section -->
</form>
<!-- wp:pattern {"slug":"amnesty/filters-active"} /-->
