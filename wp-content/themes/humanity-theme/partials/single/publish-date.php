<?php

/**
 * Post single partial, published date
 *
 * @package Amnesty\Partials
 */

if ( ! amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) ) ) {
	return;
}

$post_published = amnesty_locale_date( get_post_time( 'U', true ) );

?>

<div class="article-metaDataRow">
	<?php do_action( 'amnesty_before_published_date' ); ?>
	<time class="publishedDate" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Post published timestamp', 'amnesty' ); ?>"><?php echo esc_html( $post_published ); ?></time>
	<?php do_action( 'amnesty_after_published_date' ); ?>
</div>
