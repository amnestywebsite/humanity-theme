<?php

/**
 * Title: Author Biography
 * Description: Outputs the Author biography, if any
 * Slug: amnesty/author-bio
 * Inserter: no
 */

$author_bio = get_the_author_meta( 'authorbiographysection' );

if ( ! $author_bio ) {
	return;
}

$avatar = get_the_author_meta( 'authoravatar_id' );

?>

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Biography', 'amnesty' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:group {"tagName":"div","className":"biography-container u-cf"} -->
<div class="wp-block-group biography-container u-cf">
	<?php if ( $avatar ) : ?>
	<!-- wp:image {"id":<?php echo absint( $avatar ); ?>,"aspectRatio":"1","scale":"cover","sizeSlug":"thumbnail","linkDestination":"none","align":"left","hideImageCopyright":true} -->
	<figure class="wp-block-image alignleft size-thumbnail"><img src="<?php echo esc_url( wp_get_attachment_image_src( absint( $avatar ) )[0] ); ?>" alt="" class="wp-image-<?php echo absint( $avatar ); ?>" style="aspect-ratio:1;object-fit:cover"/></figure>
	<!-- /wp:image -->
	<?php endif; ?>
	<!-- wp:group {"tagName":"div","className":"author-biography"} -->
	<div class="author-biography">
		<?php echo wp_kses_post( wpautop( $author_bio ) ); ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
