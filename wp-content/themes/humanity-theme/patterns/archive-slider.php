<?php

/**
 * Title: Archive slider
 * Description: Generates a slider for all posts that are marked as featured for the current category
 * Slug: amnesty/archive-slider
 * Inserter: no
 */

$current_term = get_queried_object();

// site editor doesn't have current category context. mock it.
if ( is_admin() ) {
	$current_term = get_terms(
		[
			'taxonomy' => 'category',
			'number'   => 1,
			'parent'   => 0,
		]
	);

	if ( is_array( $current_term ) ) {
		$current_term = array_shift( $current_term );
	}
}

if ( ! is_a( $current_term, WP_Term::class ) || 'category' !== $current_term->taxonomy ) {
	return;
}

$slider_items = get_archive_slider_posts( $current_term );

if ( ! $slider_items ) {
	return;
}

?>

<!-- wp:group {"tagName":"div","className":"archive-slider"} -->
<div class="wp-block-group archive-slider">
	<!-- wp:amnesty-core/block-slider {"hasArrows":true,"hasContent":true,"hideContent":false,"showTabs":true,"sliderId":"categoryslider","sliderTitle":"","slides":<?php echo wp_json_encode( $slider_items, JSON_UNESCAPED_UNICODE ); ?>,"timelineCaptionStyle":"dark"} /-->
</div>
<!-- /wp:group -->
