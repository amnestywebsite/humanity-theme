<?php

/**
 * Title: Post Index Number
 * Description: Output the AIDAN index number for an entity
 * Slug: amnesty/post-index-number
 * Inserter: no
 */

$index_number = get_blog_post_meta( $post->blog_id ?? get_current_blog_id(), get_the_ID(), 'amnesty_index_number', true );
$index_number = str_replace( ' ', '&nbsp;', $index_number );

if ( ! $index_number ) {
	return;
}
?>

<span class="indexNumber"><?php echo esc_html__( 'Index Number:', 'aibrand' ), '&nbsp;', esc_html( $index_number ); ?></span>
