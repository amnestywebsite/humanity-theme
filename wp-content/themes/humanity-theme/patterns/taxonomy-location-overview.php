<?php

/**
 * Title: Locations Taxonomy overview
 * Description: Outputs a location's overview
 * Slug: amnesty/taxonomy-location-overview
 * Inserter: no
 */

$location_object = get_queried_object();
$report_intro    = get_annual_report_intro( $location_object ) ?: '';
$template_type   = get_term_meta( $location_object?->term_id, 'type', true );

$report_link = '#';
if ( is_a( $location_object, WP_Term::class ) ) {
	$report_link = get_annual_report_link( $location_object );
}

do_action( 'amnesty_location_template_before_overview', $template_type );

?>
<!-- wp:group {"tagName":"section","className":"section section--small"} -->
<section id="overview" class="wp-block-group section section--small">
	<!-- wp:group {"tagName":"div","className":"container has-gutter"} -->
	<div class="wp-block-group container has-gutter">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'Overview', 'shown above description content', 'aitc' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"placeholder":"<?php esc_html_e( 'Report intro text', 'amnesty' ); ?>"} -->
		<p class="wp-block-paragraph"><?php echo wp_kses_post( wp_strip_all_tags( $report_intro ) ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"start"}} -->
		<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-light"} -->
		<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $report_link ); ?>">
		<span><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'Read More', 'link to annual report', 'aitc' ); ?></span></a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
<?php

do_action( 'amnesty_location_template_after_overview', $template_type );
