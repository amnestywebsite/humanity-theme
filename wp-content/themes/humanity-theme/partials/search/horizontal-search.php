<?php

/**
 * Search partial, search box
 *
 * @package Amnesty\Partials
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

<form class="horizontal-search" action="<?php echo esc_url( $search_url ); ?>">
	<section class="section section--small section--dark postlist-categoriesContainer">
		<div class="container initial-filters">
			<div class="default-search-filters taxonomyArchive-filters">
				<div class="search-input">
					<?php get_template_part( 'partials/search/forminput' ); ?>
					<button class=" btn search-button" type="submit">
						<span class="search-button-text"><?php /* translators: [front] */ esc_html_e( 'Search', 'amnesty' ); ?></span>
						<i class="icon icon-search"></i>
					</button>
				</div>

				<div class="basic-filters">
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

				<span class="btn toggle-search-filters">
					<span class="toggle-search-filters-text">
						<?php /* translators: [front] */ esc_html_e( 'Filters', 'amnesty' ); ?>
					</span>
					<i class="icon icon-arrow-down"></i>
				</span>
			</div>
		</div>
		<div class="container additional-filters">
			<?php get_template_part( 'partials/search/filters' ); ?>
		</div>
	</section>
</form>

<?php
get_template_part( 'partials/archive/filters-active' );
