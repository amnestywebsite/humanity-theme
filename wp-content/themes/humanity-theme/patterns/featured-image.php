<?php

/**
 * Title: Featured Image
 * Description: Featured image output with caption, copyright, etc.
 * Slug: amnesty/featured-image
 * Inserter: no
 */

use Amnesty\Get_Image_Data;

if ( amnesty_post_has_hero() ) {
	return;
}

if ( get_post_meta( get_the_ID(), '_hide_featured_image', true ) ) {
	return;
}

$image_id = get_post_thumbnail_id( get_the_ID() );

if ( ! $image_id ) {
	return;
}

$image = new Get_Image_Data( $image_id );

$include_caption = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_hide_featured_image_caption', true ) );

?>
<!-- wp:group {"tagName":"div","className":"container container--feature","layout":{"type":"constrained"}} -->
<div class="wp-block-group container container--feature">
	<!-- wp:group {"tagName":"figure","className":"article-figure is-stretched <?php $image->credit() && print 'has-caption'; ?>","layout":{"type":"constrained"}} -->
	<figure class="article-figure is-stretched <?php $image->credit() && print 'has-caption'; ?>">
	<!-- wp:image {"id":<?php echo absint( $image_id ); ?>,"sizeSlug":"hero-md","linkDestination":"none","align":"full"} -->
	<figure class="wp-block-image alignfull size-hero-md"><?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'hero-md' ) ); ?><?php echo wp_kses_post( $image->metadata( include_caption: $include_caption ) ); ?></figure>
	<!-- /wp:image -->
	</figure>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
