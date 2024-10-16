<?php

/**
 * Title: Author Hero
 * Description: Outputs the Author page' hero, if any
 * Slug: amnesty/author-hero
 * Inserter: no
 */

if ( ! get_the_author_meta( 'authorbanner_id' ) ) {
	return;
}

$data = [
	'content'         => wp_strip_all_tags( apply_filters( 'the_content', get_the_author_meta( 'authordescriptionsection' ) ) ),
	'featuredImageId' => absint( get_the_author_meta( 'authorbanner_id' ) ),
	'title'           => wp_strip_all_tags( apply_filters( 'the_title', get_the_author_meta( 'display_name' ) ) ),
	'type'            => 'image',
];

?>

<!-- wp:amnesty-core/hero <?php echo wp_json_encode( $data, JSON_UNESCAPED_UNICODE ); ?> /-->
