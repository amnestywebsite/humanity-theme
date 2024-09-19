<?php

/**
 * Title: Related Content
 * Description: Output Related Content for a post
 * Slug: amnesty/related-content
 * Keywords: related, posts
 * Category:
 */

if ( ! amnesty_feature_is_enabled( 'related-content-posts' ) || 'attachment' === get_post_type() ) {
	return;
}

if ( wp_validate_boolean( get_post_meta( get_the_ID(), 'disable_related_content', true ) ) ) {
	return;
}

$related = new \Amnesty\Related_Content( output: false );

echo wp_kses_post( $related->get_raw() );
