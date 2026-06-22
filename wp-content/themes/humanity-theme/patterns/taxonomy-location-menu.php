<?php

/**
 * Title: Locations Taxonomy menu
 * Description: Outputs menu block for locations templates
 * Slug: amnesty/taxonomy-location-menu
 * Inserter: no
 */

$menu_options = [
	[
		'id'    => 'overview',
		/* translators: [front] Countries Locations Page */
		'label' => __( 'Overview', 'aitc' ),
	],
];

if ( have_posts() ) {
	$menu_options[] = [
		'id'    => 'news',
		/* translators: [front] Countries Locations Page */
		'label' => __( 'News', 'aitc' ),
	];
}

$template_type = get_term_meta( get_queried_object_id(), 'type', true ) ?: 'default';
$menu_options  = apply_filters( 'amnesty_location_template_menu_options', $menu_options, $template_type );

$block_attributes = [
	'color' => 'dark',
	'type'  => 'custom-menu',
	'items' => $menu_options,
];

do_action( 'amnesty_location_template_before_menu', $template_type );

?>
<!-- wp:group {"tagName":"section","className":"section section--no-padding"} -->
<section class="wp-block-group section section--no-padding">
	<!-- wp:group {"tagName":"div","className":"container has-gutter"} -->
	<div class="wp-block-group container has-gutter">
		<!-- wp:amnesty-core/block-menu <?php echo wp_kses_data( wp_json_encode( $block_attributes ) ); ?> /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
<?php

do_action( 'amnesty_location_template_after_menu', $template_type );
