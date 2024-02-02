<?php

/**
 * Post single partial, metadata
 *
 * @package Amnesty\Partials
 */

$should_switch = $args['should_switch'] ?? false;

if ( $should_switch ) {
	switch_to_blog( $post->blog_id );
}

$show_back_link    = ! amnesty_validate_boolish( amnesty_get_option( '_display_category_label' ) );
$show_share_icons  = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_disable_share_icons', true ) );
$show_byline       = amnesty_validate_boolish( get_post_meta( get_the_ID(), '_display_author_info', true ) );
$show_publish_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_published_date', true ) );
$show_updated_date = amnesty_validate_boolish( get_post_meta( get_the_ID(), 'show_updated_date', true ) );
$main_category     = amnesty_get_a_post_term( get_the_ID() );

if ( $should_switch ) {
	restore_current_blog();
}

$show_top_row    = ( $main_category && $show_back_link ) || $show_share_icons;
$show_bottom_row = $show_byline || $show_publish_date || $show_updated_date;

?>
<div class="article-meta" role="contentinfo">
<?php if ( $show_top_row ) : ?>
	<div class="article-metaActions">
	<?php if ( $main_category && $show_back_link ) : ?>
		<a class="btn btn--white has-icon" aria-label="<?php echo esc_attr( sprintf( /* translators: [front] Link to return to all posts of category %s */ __( 'Back to %s', 'amnesty' ), $main_category->name ) ); ?>" href="<?php echo esc_url( amnesty_term_link( $main_category ) ); ?>">
			<span class="icon-arrow-left"></span>
			<span><?php echo esc_html( $main_category->name ); ?></span>
		</a>
	<?php endif; ?>

	<?php if ( $show_share_icons ) : ?>
		<?php get_template_part( 'partials/article-share' ); ?>
	<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ( $show_bottom_row ) : ?>
	<div class="article-metaData">
		<div>
		<?php

		if ( $should_switch ) {
			switch_to_blog( $post->blog_id );
		}

		get_template_part( 'partials/single/byline' );
		get_template_part( 'partials/single/publish-date' );

		?>
		</div>

	<?php

	get_template_part( 'partials/single/updated-date' );

	if ( $should_switch ) {
		restore_current_blog();
	}

	?>
	</div>
<?php endif; ?>
</div>
