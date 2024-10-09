<?php

/**
 * Title: Attachment Term List
 * Description: Output the taxonomy terms for an attachment
 * Slug: amnesty/attachment-term-list
 * Inserter: no
 */

$should_switch_blog = ! empty( $post->blog_id ) && absint( $post->blog_id ) !== absint( get_current_blog_id() );

if ( $should_switch_blog ) {
	switch_to_blog( $post->blog_id );
}


$attachment_terms = wp_get_object_terms( get_the_ID(), get_object_taxonomies( get_post_type() ) );

if ( $should_switch_blog ) {
	restore_current_blog();
}

if ( empty( $attachment_terms ) ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"article-metaData"} -->
<div class="wp-block-group article-metaData">
<!-- wp:group {"tagName":"div","className":"topics-container"} -->
<div class="wp-block-group topics-container">
<!-- wp:list -->
<ul class="wp-block-list">
<?php foreach ( $attachment_terms as $attachment_term ) : ?>
<!-- wp:list-item -->
<li class="wp-block-list-item">
<a href="<?php echo esc_url( amnesty_cross_blog_term_link( $attachment_term, $should_switch_blog ) ); ?>"><?php echo esc_html( $attachment_term->name ); ?></a>
</li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
