<?php

/**
 * Title: Attachment Term List
 * Description: Output the taxonomy terms for an attachment
 * Slug: amnesty/attachment-term-list
 * Inserter: no
 */

use function Inpsyde\MultilingualPress\translationIds;

global $post;

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

foreach ( $attachment_terms as $index => $remote_term ) {
	$relations = translationIds( $remote_term->term_id, 'term', $post?->blog_id ?? 0 );

	if ( ! isset( $relations[ get_current_blog_id() ] ) ) {
		continue;
	}

	$local_term = get_term( $relations[ get_current_blog_id() ], $remote_term->taxonomy );

	if ( is_a( $local_term, WP_Term::class ) ) {
		$attachment_terms[ $index ] = $local_term;
	}
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
<a href="<?php echo esc_url( amnesty_term_link( $attachment_term ) ); ?>"><?php echo esc_html( $attachment_term->name ); ?></a>
</li>
<!-- /wp:list-item -->
<?php endforeach; ?>
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
