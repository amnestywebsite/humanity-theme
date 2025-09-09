<?php

/**
 * Title: Post Byline (Author)
 * Description: Output the public byline for a post
 * Slug: amnesty/post-byline-author
 * Inserter: no
 */

$enabled = get_post_meta( get_the_ID(), '_display_author_info', true );

if ( ! $enabled ) {
	return;
}

$use_author = get_post_meta( get_the_ID(), 'byline_is_author', true );

if ( ! $use_author ) {
	return;
}

$author_id      = get_the_author_meta( 'ID' );
$twitter_handle = get_the_author_meta( 'twitter' );
$author_avatar  = get_avatar( $author_id, 60 );

$author_url = sprintf(
	'<a class="authorName" href="%s">%s</a>',
	esc_url( get_author_posts_url( $author_id ) ),
	esc_html( get_the_author_meta( 'display_name', $author_id ) )
);

?>
<!-- wp:group {"tagName":"div","className":"bylineContainer"} -->
<div class="wp-block-group bylineContainer">
<?php if ( $author_avatar ) : ?>
	<!-- wp:group {"tagName":"div","className":"avatarContainer"} -->
	<div class="wp-block-group avatarContainer">
		<a class="authorAvatar"><?php echo wp_kses_post( $author_avatar ); ?></a>
	</div>
<?php endif; ?>
	<!-- wp:group {"tagName":"div","className":"authorInfoContainer"} -->
	<div class="wp-block-group authorInfoContainer">
		<?php /* translators: [front] %s: prefix for a post's author attribution */ ?>
		<?php echo wp_kses_post( sprintf( _x( 'By %s', "prefix for a post's author attribution", 'amnesty' ), $author_url ) ); ?>
	<?php if ( $twitter_handle ) : ?>
		<a class="authorTwitter" rel="noopener noreferer" target="_blank" href="https://twitter.com/<?php echo esc_attr( $twitter_handle ); ?>"><?php echo esc_html( $twitter_handle ); ?></a>
	<?php endif; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
