<?php

/**
 * Title: Locations Taxonomy country selection
 * Description: Outputs country selection dropdown
 * Slug: amnesty/taxonomy-location-country-selection
 * Inserter: no
 */

$object  = get_queried_object();
$options = [];

if ( is_a( $object, WP_Term::class ) ) {
	$top_level = get_term_top_most_parent( $object->term_id, $object->taxonomy );
	$countries = amnesty_get_locations_by_type( [ 'term' => $top_level ] );

	$options = [
		amnesty_term_link( $top_level ) => $top_level->name,
	];

	foreach ( $countries as $child ) {
		$options[ amnesty_term_link( $child ) ] = $child->name;
	}
}

if ( ! count( $options ) && is_admin() ) {
	$options['-'] = __( 'Region name', 'amnesty' );
	$options['~'] = __( 'Country within region', 'amnesty' );
}

$select_args = [
	/* translators: [front] Countries Locations Page */
	'label'      => _x( 'View other countries in', 'shown before region name', 'aitc' ),
	'show_label' => true,
	'options'    => $options,
	'is_nav'     => true,
	'type'       => 'nav',
];

?>
<!-- wp:amnesty-core/custom-select <?php echo wp_json_encode( $select_args ); ?> /-->

<!-- wp:group {"tagName":"section","className":"section section--small section--countrySelect"} -->
<section class="wp-block-group section section--small section--countrySelect">
	<!-- wp:group {"tagName":"div","className":"container has-gutter"} -->
	<div class="wp-block-group container has-gutter">
	<!-- wp:html {"content":"<?php echo wp_json_encode( $html_content ); ?>"} -->
	<?php echo $html_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped at source ?>
	<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
