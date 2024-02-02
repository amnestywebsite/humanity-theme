<?php

/**
 * Post single partial, topics container
 *
 * @package Amnesty\Partials
 */

$should_switch = $args['should_switch'] ?? false;

if ( isset( $post->blog_id ) ) {
	$should_switch = absint( $post->blog_id ) !== absint( get_current_blog_id() );
}

$post_terms = [];

if ( $should_switch ) {
	switch_to_blog( $post->blog_id );

	$post_terms = wp_get_object_terms( get_the_ID(), get_taxonomies_for_attachments() );

	restore_current_blog();
} else {
	$post_terms = amnesty_get_post_terms( get_the_ID() );
}

if ( empty( $post_terms ) ) {
	return;
}

?>

<div class="article-metaData">
	<div class="topics-container">
		<ul>
		<?php foreach ( $post_terms as $topic ) : ?>
			<li><a href="<?php echo esc_url( amnesty_cross_blog_term_link( $topic, $should_switch ) ); ?>"><?php echo esc_html( $topic->name ); ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
