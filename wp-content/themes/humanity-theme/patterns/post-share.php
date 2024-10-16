<?php

/**
 * Title: Post Share
 * Description: Output article sharing links
 * Slug: amnesty/post-share
 * Inserter: no
 */

$show_share_icons = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_disable_share_icons', true ) );

if ( ! $show_share_icons ) {
	return;
}

spaceless();

?>

<!-- wp:list {"className":"article-share"} -->
<ul class="wp-block-list article-share"><!-- wp:list-item -->
	<li>
		<a class="article-shareFacebook" target="_blank" rel="noreferrer noopener" href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover  */ esc_attr_e( 'Share on Facebook', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( amnesty_asset_uri( 'images' ) ); ?>/icon-facebook.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Facebook Logo', 'amnesty' ); ?>">
		</a>
	</li>
	<!-- /wp:list-item -->
	<!-- wp:list-item -->
	<li>
		<a class="article-shareTwitter" target="_blank" rel="noreferrer noopener" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover */ esc_attr_e( 'Share on Twitter', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( amnesty_asset_uri( 'images' ) ); ?>/icon-twitter.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Twitter Logo', 'amnesty' ); ?>">
		</a>
	</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<?php

endspaceless();
