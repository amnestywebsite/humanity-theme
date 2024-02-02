<?php

/**
 * Global partial, share container
 *
 * @package Amnesty\Partials
 */

spaceless();

?>
<ul class="article-share" role="complementary" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ Accessibility text */ esc_attr_e( 'Social sharing options', 'amnesty' ); ?>">
	<li>
		<a class="article-shareFacebook" target="_blank" rel="noreferrer noopener" href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover  */ esc_attr_e( 'Share on Facebook', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( amnesty_asset_uri( 'images' ) ); ?>/icon-facebook.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Facebook Logo', 'amnesty' ); ?>">
		</a>
	</li>
	<li>
		<a class="article-shareTwitter" target="_blank" rel="noreferrer noopener" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" title="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ text shows on hover */ esc_attr_e( 'Share on Twitter', 'amnesty' ); ?>">
			<img src="<?php echo esc_url( amnesty_asset_uri( 'images' ) ); ?>/icon-twitter.svg" alt="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ esc_attr_e( 'Twitter Logo', 'amnesty' ); ?>">
		</a>
	</li>
</ul>
<?php endspaceless(); ?>
