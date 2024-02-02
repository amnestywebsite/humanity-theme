<?php

/**
 * Post partial, small image
 *
 * @package Amnesty\Partials
 */

$image_id = get_post_thumbnail_id( get_the_ID() );
$credit   = amnesty_get_image_credit( $image_id );

?>
<article class="post postImage--small <?php echo esc_attr( $credit ? 'aimc-ignore' : '' ); ?>" aria-label="Article: <?php echo esc_attr( format_for_aria_label( get_the_title() ) ); ?>">
	<figure class="post-figure">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php echo wp_get_attachment_image( $image_id, 'post-half', attr: [ 'alt' => '' ] ); ?>
		</a>
	</figure>
	<?php get_template_part( 'partials/post/post', 'content' ); ?>
</article>
