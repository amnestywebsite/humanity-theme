<?php

/**
 * Title: Featured Image
 * Description: Featured image output with caption, copyright, etc.
 * Slug: amnesty/featured-image
 * Inserter: yes
 */

use Amnesty\Get_Image_Data;

if ( amnesty_post_has_hero() || amnesty_post_has_header() ) {
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

$attributes = [
	'id'              => $image_id,
	'className'       => 'article-figure is-stretched' . ( $image->credit() ? ' has-caption' : '' ),
	'sizeSlug'        => 'hero-md',
	'linkDestination' => 'none',
];

?>
<!-- wp:group {"tagName":"div","className":"container container--feature"} -->
<div class="wp-block-group container container--feature">
	<!-- wp:image <?php echo wp_kses_data( wp_json_encode( $attributes ) ); ?> -->
	<figure class="wp-block-image <?php echo esc_attr( $attributes['className'] ); ?>"><img src="<?php echo esc_url( wp_get_attachment_url( $image_id ) ); ?>" alt="" class="wp-image-<?php echo absint( $image_id ); ?>"/></figure>
	<!-- /wp:image -->
</div>
<!-- /wp:group -->
