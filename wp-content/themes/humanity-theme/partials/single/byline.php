<?php

/**
 * Post single partial, byline
 *
 * @package Amnesty\Partials
 */

$enabled = get_post_meta( get_the_ID(), '_display_author_info', true );

if ( ! $enabled ) {
	return;
}

$use_author = get_post_meta( get_the_ID(), 'byline_is_author', true );

if ( ! $use_author ) :
	$byline_entity  = get_post_meta( get_the_ID(), 'byline_entity', true );
	$byline_context = get_post_meta( get_the_ID(), 'byline_context', true );

	if ( ! $byline_entity && ! $byline_context ) {
		return;
	}

	?>

<div class="bylineContainer">
	<div class="authorInfoContainer">
	<?php

	if ( $byline_entity ) {
		/* translators: [front] */
		echo wp_kses_post( sprintf( '<span>%s %s</span>', _x( 'By', 'author attribution (as in "written by")', 'amnesty' ), $byline_entity ) );
	}

	if ( $byline_context ) {
		echo wp_kses_post( sprintf( ',&nbsp;<span>%s</span>', $byline_context ) );
	}

	?>
	</div>
</div>

	<?php
	return;
endif;


$author_id      = get_the_author_meta( 'ID' );
$author_name    = get_the_author_meta( 'display_name' );
$twitter_handle = get_the_author_meta( 'twitter' );
$author_avatar  = get_avatar( $author_id, 60 );

$author_url = sprintf(
	'<a class="authorName" href="%s">%s</a>',
	esc_url( get_author_posts_url( $author_id ) ),
	esc_html( get_the_author_meta( 'display_name', $author_id ) )
);

?>

<div class="bylineContainer">
<?php if ( $author_avatar ) : ?>
	<div class="avatarContainer">
		<a class="authorAvatar">
			<?php echo wp_kses_post( $author_avatar ); ?>
		</a>
	</div>
<?php endif; ?>
	<div class="authorInfoContainer">
		<?php /* translators: [front] %s: prefix for a post's author attribution */ ?>
		<?php echo wp_kses_post( sprintf( _x( 'By %s', "prefix for a post's author attribution", 'amnesty' ), $author_url ) ); ?>
	<?php if ( $twitter_handle ) : ?>
		<a class="authorTwitter" rel="noopener noreferer" target="_blank" href="https://twitter.com/<?php echo esc_attr( $twitter_handle ); ?>"><?php echo esc_html( $twitter_handle ); ?></a>
	<?php endif; ?>
	</div>
</div>
