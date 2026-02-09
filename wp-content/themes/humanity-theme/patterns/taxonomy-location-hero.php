<?php

/**
 * Title: Locations Taxonomy hero
 * Description: Outputs hero block for taxonomy archive pages
 * Slug: amnesty/taxonomy-location-hero
 * Inserter: no
 */

$object   = get_queried_object();
$image_id = absint( get_term_meta( $object?->term_id, 'image_id', true ) );

// if the term is a report with no image, grab its parent term image instead
if ( ! $image_id && 'report' === get_term_meta( $object?->term_id, 'type', true ) ) {
	$image_id = absint( get_term_meta( get_term_parent( $object )?->term_id, 'image_id', true ) );
}

$hero_data = [
	'title'            => $object?->name,
	'content'          => $object->labels?->archives ?? $object?->description,
	'imageID'          => $image_id,
	'hideImageCaption' => false,
];

if ( is_a( $object, WP_Term::class ) ) {
	$disclaimer = fn (): string => strval( amnesty_get_location_disclaimer( $object ) );
} else {
	$disclaimer = fn ( ?string $text ): ?string => $text;
}

add_filter( 'amnesty_image_data_caption', $disclaimer );
printf( '<!-- wp:amnesty-core/hero %s /-->', wp_kses_data( wp_json_encode( $hero_data, JSON_UNESCAPED_UNICODE ) ) );
remove_filter( 'amnesty_image_data_caption', $disclaimer );
