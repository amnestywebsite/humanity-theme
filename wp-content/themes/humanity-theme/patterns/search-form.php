<?php

/**
 * Title: Search Form
 * Description: Search form output
 * Slug: amnesty/search-form
 * Inserter: yes
 */
$years  = range( gmdate( 'Y' ), 1961 );
$months = amnesty_get_months();

$year_options  = [ 0 => __( 'Year', 'amnesty' ) ] + array_combine( $years, $years );
$month_options = [ 0 => __( 'Month', 'amnesty' ) ] + $months;

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

$year_attributes = [
	'className' => 'autosubmit for-year',
	'label'     => __( 'Year', 'amnesty' ),
	'showLabel' => false,
	'name'      => 'qyear',
	'isNav'     => true,
	'multiple'  => false,
	'options'   => $year_options,
	'active'    => $year_param,
];

$month_attributes = [
	'className'  => 'autosubmit for-month' . ( $year_param ? ' is-active' : '' ),
	'label'      => __( 'Month', 'amnesty' ),
	'showLabel'  => false,
	'name'       => 'qmonth',
	'isNav'      => true,
	'multiple'   => false,
	'options'    => (object) $month_options,
	'active'     => $month_param,
	'isDisabled' => ! $year_param,
];

?>
<!-- wp:group {"tagName":"form","className":"horizontal-search","action":"<?php echo esc_url( $search_url ); ?>"} -->
<form class="wp-block-group horizontal-search" action="<?php echo esc_url( $search_url ); ?>">
	<!-- wp:group {"tagName":"section","className":"section section--small section--dark postlist-categoriesContainer"} -->
	<section class="wp-block-group section section--small section--dark postlist-categoriesContainer">
		<!-- wp:group {"tagName":"div","className":"container initial-filters"} -->
		<div class="wp-block-group container initial-filters">
			<!-- wp:group {"tagName":"div","className":"default-search-filters taxonomyArchive-filters"} -->
			<div class="wp-block-group default-search-filters taxonomyArchive-filters">
				<!-- wp:group {"tagName":"div","className":"search-input"} -->
				<div class="wp-block-group search-input">
					<!-- wp:amnesty-core/search-input /-->

					<!-- wp:buttons -->
					<div class="wp-block-buttons"><!-- wp:button {"tagName":"button","className":"is-style-search","type":"submit"} -->
					<div class="wp-block-button is-style-search"><button type="submit" class="wp-block-button__link wp-element-button">
						<span class="search-button-text"><?php /* translators: [front] */ esc_html_e( 'Search', 'amnesty' ); ?></span>
						<i class="icon icon-search"></i>
					</button></div>
					<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"tagName":"div","className":"basic-filters"} -->
				<div class="wp-block-group basic-filters">
				<?php do_action( 'amnesty_search_before_basic_filters' ); ?>

				<!-- wp:amnesty-core/custom-select <?php echo wp_kses_data( wp_json_encode( $year_attributes ) ); ?> /-->
				<!-- wp:amnesty-core/custom-select <?php echo wp_kses_data( wp_json_encode( $month_attributes ) ); ?> /-->

				<?php do_action( 'amnesty_search_after_basic_filters' ); ?>
				</div>
				<!-- /wp:group -->

				<span class="btn toggle-search-filters">
					<span class="toggle-search-filters-text">
						<?php /* translators: [front] */ esc_html_e( 'Filters', 'amnesty' ); ?>
					</span>
					<i class="icon icon-arrow-down"></i>
				</span>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"tagName":"div","className":"container additional-filters"} -->
		<div class="container additional-filters">
			<!-- wp:pattern {"slug":"amnesty/archive-filters"} /-->
		</div>
		<!-- /wp:group -->
	</section>
	<!-- /wp:group -->
</form>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"amnesty/active-filters"} /-->
