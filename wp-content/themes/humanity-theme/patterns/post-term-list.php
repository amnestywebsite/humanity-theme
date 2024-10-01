<?php

/**
 * Title: Post Term List
 * Description: Output the taxonomy terms for a post
 * Slug: amnesty/post-term-list
 * Inserter: no
 */

$post_terms = wp_get_object_terms( get_the_ID(), get_object_taxonomies( get_post_type() ) );

if ( empty( $post_terms ) ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"article-metaData"} -->
<div class="wp-block-group article-metaData">
<!-- wp:group {"tagName":"div","className":"topics-container"} -->
<div class="wp-block-group topics-container">
<!-- wp:list -->
<ul class="wp-block-list">
<?php foreach ( $post_terms as $post_term ) : ?>
<!-- wp:list-item -->
<li class="wp-block-list-item">
<a href="<?php echo esc_url( amnesty_term_link( $post_term ) ); ?>"><?php echo esc_html( $post_term->name ); ?></a>
</li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
