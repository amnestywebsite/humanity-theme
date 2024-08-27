<?php

/**
 * Title: Post Published Date
 * Description: Output the published date metadata for a post
 * Slug: amnesty/post-published-date
 * Inserter: no
 */

if ( ! amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) ) ) {
	return;
}

$post_published = amnesty_locale_date( get_post_time( 'U', true ) );

?>
<!-- wp:group {"tagName":"div","className":"article-metaDataRow"} -->
<div class="wp-block-group article-metaDataRow">
	<?php do_action( 'amnesty_before_published_date' ); ?>
	<time class="publishedDate" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Post published timestamp', 'amnesty' ); ?>"><?php echo esc_html( $post_published ); ?></time>
	<?php do_action( 'amnesty_after_published_date' ); ?>
</div>
<!-- /wp:group -->
