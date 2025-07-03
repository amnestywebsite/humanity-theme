<?php

/**
 * Title: Attachment Published Date
 * Description: Output the published date metadata for an attachment
 * Slug: amnesty/attachment-published-date
 * Inserter: no
 */

global $post;

$should_switch_blog = ! empty( $post->blog_id ) && absint( $post->blog_id ) !== absint( get_current_blog_id() );

if ( $should_switch_blog ) {
	switch_to_blog( $post->blog_id );
}

$show_publish_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) );

if ( $should_switch_blog ) {
	restore_current_blog();
}

if ( ! $show_publish_date ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"article-metaDataRow"} -->
<div class="wp-block-group article-metaDataRow">
<?php

do_action( 'amnesty_before_published_date' );

try {
	$datetime = new DateTime( $post->post_date_gmt, new DateTimeZone( 'GMT' ) );
	$format   = get_option( 'date_format' );
} catch ( DateMalformedStringException $e ) {
	return;
}

?>

<!-- wp:html -->
<div class="publishedDate wp-block-post-date"><time datetime="<?php echo esc_attr( $datetime->format( 'c' ) ); ?>"><?php echo esc_html( $datetime->format( $format ) ); ?></time></div>
<!-- /wp:html -->

<?php

do_action( 'amnesty_after_published_date' );

?>
</div>
<!-- /wp:group -->
