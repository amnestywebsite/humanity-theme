<?php

$has_credit = false;

if ( $data['featured_image_id'] ?? false ) {
	$has_credit = (bool) amnesty_get_image_credit( $data['featured_image_id'] );
}

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( get_the_title() ) );

$background_image = '';

if ( $data['featured_image'] ) {
	$background_image = sprintf( 'background-image: url(%s);', $data['featured_image'] );
}

?>
<li>
	<article class="linkList-item <?php echo esc_attr( $has_credit ? 'aimc-ignore' : '' ); ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>" style="aiic:ignore; <?php echo esc_attr( $background_image ); ?>" tabindex="0">
	<?php require realpath( __DIR__ . '/list-item-meta.php' ); ?>
	<?php require realpath( __DIR__ . '/list-item-title.php' ); ?>

		<div class="postInfo-container">
		<?php require realpath( __DIR__ . '/list-item-date.php' ); ?>

		<?php if ( $show_post_date && $show_author ) : ?>
			<span class="linkList-authorDivider"></span>
		<?php endif; ?>

		<?php require realpath( __DIR__ . '/list-item-author.php' ); ?>
		</div>
	</article>
</li>
