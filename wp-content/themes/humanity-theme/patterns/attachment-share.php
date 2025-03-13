<?php

/**
 * Title: Attachment Share
 * Description: Output article sharing links
 * Slug: amnesty/attachment-share
 * Inserter: no
 */

$should_switch_blog = ! empty( $post->blog_id ) && absint( $post->blog_id ) !== absint( get_current_blog_id() );

if ( $should_switch_blog ) {
	switch_to_blog( $post->blog_id );
}

$show_share_icons = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_disable_share_icons', true ) );

if ( $should_switch_blog ) {
	restore_current_blog();
}

if ( ! $show_share_icons ) {
	return;
}

$image_base_uri = get_template_directory_uri() . '/images';

spaceless();

?>

<!-- wp:list {"className":"article-share"} -->
<ul class="wp-block-list article-share"><!-- wp:list-item -->
	<li>
		<a class="article-shareFacebook" target="_blank" rel="noreferrer noopener" href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover  */ esc_attr_e( 'Share on Facebook', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( $images_base_uri ); ?>/icon-facebook.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Facebook Logo', 'amnesty' ); ?>">
		</a>
	</li>
	<!-- /wp:list-item -->
	<!-- wp:list-item -->
	<li>
		<a class="article-shareTwitter" target="_blank" rel="noreferrer noopener" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover */ esc_attr_e( 'Share on Twitter', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( $images_base_uri ); ?>/icon-twitter.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Twitter Logo', 'amnesty' ); ?>">
		</a>
	</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<?php

endspaceless();
