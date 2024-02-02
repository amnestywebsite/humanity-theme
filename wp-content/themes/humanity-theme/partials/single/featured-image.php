<?php

/**
 * Post single partial, featured image
 *
 * @package Amnesty\Partials
 */

use Amnesty\Get_Image_Data;

if ( get_post_meta( get_the_ID(), '_hide_featured_image', true ) ) {
	return;
}

$image_id = get_post_thumbnail_id( get_the_ID() );

if ( ! $image_id ) {
	return;
}

$image = new Get_Image_Data( $image_id );

?>

<div class="container container--feature">
	<figure class="article-figure is-stretched <?php $image->credit() && print 'has-caption'; ?>">
	<?php

	echo wp_kses_post( wp_get_attachment_image( $image_id, 'hero-md' ) );

	echo wp_kses_post(
		$image->metadata(
			include_caption: ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_hide_featured_image_caption', true ) ),
		) 
	);

	?>
	</figure>
</div>
