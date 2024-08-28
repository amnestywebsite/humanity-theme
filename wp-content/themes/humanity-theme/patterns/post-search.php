<?php

/**
 * Title: Post search pattern
 * Description: Post search pattern for the theme
 * Slug: amnesty/post-search
 * Inserter: no
 */

global $post;

$should_switch = isset( $post->blog_id ) && absint( $post->blog_id ) !== get_current_blog_id();

if ( $should_switch ) {
	switch_to_blog( absint( $post->blog_id ) );
}

$topic        = amnesty_get_a_post_term( get_the_ID(), 'topic' );
$content_type = amnesty_get_a_post_term( get_the_ID(), 'category' );
$location     = amnesty_get_a_post_term( get_the_ID(), 'location' );

$epoch = get_post_time( 'U', true );
$date  = amnesty_locale_date( $epoch );

$updated = get_post_meta( get_the_ID(), 'amnesty_updated', true );
if ( $updated ) {
	$updated = wp_date( get_option( 'date_format' ), strtotime( $updated ), new DateTimeZone( 'UTC' ) );
	$updated = sprintf( '%s: %s', _x( 'Updated', 'updated date label', 'amnesty' ), $updated );
}

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( get_the_title() ) );

?>

<!-- wp:group {"tagName":"article","className":"post post--result"} -->
<article class="wp-block-group post post--result" aria-label="<?php echo esc_attr( $aria_label ); ?>">
<?php if ( $content_type ) : ?>
	<a class="post-category" href="<?php echo esc_url( amnesty_term_link( $content_type ) ); ?>" tabindex="0"><?php echo esc_html( $content_type->name ); ?></a>
<?php endif; ?>
<?php if ( $location ) : ?>
	<a class="post-location" href="<?php echo esc_url( amnesty_term_link( $location ) ); ?>" tabindex="0"><?php echo esc_html( $location->name ); ?></a>
<?php endif; ?>
<?php if ( $topic ) : ?>
	<a class="post-topic" href="<?php echo esc_url( amnesty_term_link( $topic ) ); ?>" tabindex="0"><?php echo esc_html( $topic->name ); ?></a>
<?php endif; ?>
	<!-- wp:heading {"level":1,"className":"post-title"} -->
	<h1 class="wp-block-group post-title">
	<a href="<?php the_permalink(); ?>" tabindex="0"><?php the_title(); ?></a>
	</h1>
	<!-- /wp:heading -->
	<!-- wp:group {"tagName":"div","className":"post-excerpt"} -->
	<div class="wp-block-group post-excerpt"><?php echo esc_html( trim_text( get_the_excerpt(), 300, true, true ) ); ?></div>
	<!-- /wp:group -->
	<!-- wp:group {"tagName":"span","className":"post-byline"} -->
	<span class="wp-block-group post-byline" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Post published date', 'amnesty' ); ?>"><?php echo esc_html( $date ); ?></span><!-- /wp:group -->
<?php if ( $updated ) : ?>
	<!-- wp:group {"tagName":"strong","className":"post-byline"} -->
	<strong class="wp-block-group post-byline" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Post updated date', 'amnesty' ); ?>"><?php echo esc_html( $updated ); ?></strong><!-- /wp:group -->
<?php endif; ?>
</article>
<!-- /wp:group -->

<?php

if ( $should_switch ) {
	restore_current_blog();
}

?>
