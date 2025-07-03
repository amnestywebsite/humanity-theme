<?php

/**
 * Title: Attachment Updated Date
 * Description: Output the "updated date" for an attachment
 * Slug: amnesty/attachment-updated-date
 * Inserter: no
 */

global $post;

$should_switch_blog = ! empty( $post->blog_id ) && absint( $post->blog_id ) !== absint( get_current_blog_id() );

if ( $should_switch_blog ) {
	switch_to_blog( $post->blog_id );
}

$show_updated_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_updated_date', true ) );
$post_updated_date = get_post_meta( get_the_ID(), 'amnesty_updated', true );

if ( $should_switch_blog ) {
	restore_current_blog();
}


if ( ! $show_updated_date || ! $post_updated_date ) {
	return;
}

$post_updated_date = amnesty_locale_date( strtotime( $post_updated_date ) );

?>

<span class="updatedDate" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Post updated timestamp', 'amnesty' ); ?>">
	<strong><?php echo esc_html( _x( 'Updated:', 'prefix for post updated date', 'amnesty' ) ); ?></strong>&nbsp;<time><?php echo esc_html( $post_updated_date ); ?></time>
</span>
