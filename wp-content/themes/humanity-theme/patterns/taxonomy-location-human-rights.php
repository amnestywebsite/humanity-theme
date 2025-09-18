<?php

/**
 * Title: Locations Taxonomy human rights status
 * Description: Outputs human rights status for locations
 * Slug: amnesty/taxonomy-location-human-rights
 * Inserter: no
 */

$template_type  = get_term_meta( get_queried_object_id(), 'type', true );
$global_report  = get_human_rights_report();
$penalty_status = null;

if ( function_exists( 'get_death_penalty_status' ) ) {
	$penalty_status = get_death_penalty_status( get_queried_object() );
}

// show something in the editor
if ( ! $penalty_status && is_admin() && function_exists( 'get_death_penalty_statuses' ) ) {
	$penalty_status = get_death_penalty_statuses()['retentionist'];
}

do_action( 'amnesty_location_template_before_humanrights', $template_type );

if ( ! $penalty_status && ! $global_report ) {
	do_action( 'amnesty_location_template_after_humanrights', $template_type );
	return;
}

?>
<!-- wp:group {"tagName":"section","className":"section section\u002d\u002dno-padding is-fullWidth\u002d\u002dmobile"} -->
<section id="human-rights" class="wp-block-group section section--no-padding is-fullWidth--mobile"><!-- wp:group {"className":"container has-gutter"} -->
<div class="wp-block-group container has-gutter"><!-- wp:group {"className":"human-rightsStatus","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
<div class="wp-block-group human-rightsStatus"><!-- wp:group {"className":"human-rights is-style-default","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group human-rights is-style-default"><!-- wp:heading {"level":3,"className":"human-rightsHeading"} -->
<h3 class="wp-block-heading human-rightsHeading"><?php /* translators: [front]  Countries Locations Page */ echo esc_html_x( 'Death Penalty status', 'death penalty heading', 'aitc' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:heading {"className":"human-rightsAnswer"} -->
<h2 class="wp-block-heading human-rightsAnswer"><?php echo esc_html( $penalty_status['label'] ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"wp-block-paragraph human-rightsDescription"} -->
<p class="wp-block-paragraph human-rightsDescription"><?php echo esc_html( $penalty_status['description'] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"human-rights","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}}} -->
<div class="wp-block-group human-rights" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"url":"<?php echo esc_url( wp_get_attachment_image_url( absint( $global_report['image_id'] ?? 0 ), 'lwi-block-md@2x' ) ); ?>","id":<?php echo absint( $global_report['image_id'] ?? 0 ); ?>,"dimRatio":50,"customOverlayColor":"#FFF","isUserOverlayColor":false,"focalPoint":{"x":1,"y":1},"isDark":false,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#FFF"></span><img class="wp-block-cover__image-background wp-image-<?php echo absint( $global_report['image_id'] ?? 0 ); ?>" alt="" src="<?php echo esc_url( wp_get_attachment_image_url( absint( $global_report['image_id'] ?? 0 ), 'lwi-block-md@2x' ) ); ?>" style="object-position:100% 100%" data-object-fit="cover" data-object-position="100% 100%"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","className":"human-rightsHeading u-textUpper"} -->
<h2 class="wp-block-heading has-text-align-center human-rightsHeading u-textUpper"><?php echo esc_html( sprintf( '%s %s', /* translators: [front] Countries Locations Page */ __( 'View the Amnesty International Report', 'aitc' ), $global_report['year'] ) ); ?></h2>
<!-- /wp:heading -->

<!-- wp:buttons {"className":"is-content-justification-center"} -->
<div class="wp-block-buttons is-content-justification-center"><?php foreach ( $global_report['links'] as $button ) : // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?><!-- wp:button {"className":"is-style-<?php echo esc_attr( $button['style'] ); ?>"} -->
<div class="wp-block-button is-style-<?php echo esc_attr( $button['style'] ); ?>"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $button['link'] ); ?>"><?php echo esc_html( $button['text'] ); ?></a></div>
<!-- /wp:button --><?php endforeach; // phpcs:ignore PEAR.WhiteSpace.ScopeClosingBrace.Indent ?></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
<?php

do_action( 'amnesty_location_template_after_humanrights', $template_type );
