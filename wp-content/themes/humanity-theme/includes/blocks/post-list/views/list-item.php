<?php

// translators: [front] %s: the title of the article
$aria_label = sprintf( __( 'Article: %s', 'amnesty' ), format_for_aria_label( get_the_title() ) );

?>
<li>
	<article class="linkList-item" aria-label="<?php echo esc_attr( $aria_label ); ?>">
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
