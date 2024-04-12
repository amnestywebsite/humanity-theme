<?php

/**
 * Petitions partial, item
 *
 * @package Amnesty\Partials
 */

$image_id    = get_post_thumbnail_id( get_the_ID() );
$has_credit  = (bool) amnesty_get_image_credit( $image_id );
$image_attrs = [
	'alt' => '', // presentation only
];

if ( $has_credit ) {
	$image_attrs['class'] = 'aiic-ignore';
}

?>

<div class="post actionBlock petitions-item <?php echo esc_attr( $has_credit ? 'aimc-ignore' : '' ); ?>">
<?php if ( $image_id ) : ?>
	<figure class="actionBlock-figure petitions-figure">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php echo wp_get_attachment_image( $image_id, 'post-half', attr: $image_attrs ); ?>
		</a>
	</figure>
<?php endif; ?>

	<figcaption class="actionBlock-content">
		<div class="petition-index-excerpt"><?php the_excerpt(); ?></div>
		<p class="petition-index-title"><?php the_title(); ?></p>
		<a class="btn btn--large btn--fill petition-index-btn" href="<?php the_permalink(); ?>">
		<?php

		if ( in_array( get_the_ID(), (array) $args['user_signed_petitions'], true ) ) {
			/* translators: [front] used by sections on petition forms */
			echo esc_html_x( 'Signed!', 'User has signed this petition.', 'amnesty' );
		} else {
			/* translators: [front] used by sections on petition forms */
			esc_html_e( 'Act Now', 'amnesty' );
		}

		?>
		</a>
	</figcaption>
</div>
