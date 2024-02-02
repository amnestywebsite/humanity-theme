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

$show_back_link   = ! amnesty_validate_boolish( amnesty_get_option( '_display_category_label' ) );
$show_share_icons = ! amnesty_validate_boolish( get_post_meta( get_the_ID(), '_disable_share_icons', true ) );

if ( $should_switch ) {
	switch_to_blog( $post->blog_id );
}

$main_category = amnesty_get_a_post_term( get_the_ID() );

if ( $should_switch ) {
	restore_current_blog();
}

$show_bottom_row = ( $main_category && $show_back_link ) || $show_share_icons;

?>

<div class="article-meta" role="contentinfo">
	<?php get_template_part( 'partials/single/termlist', null, compact( 'should_switch' ) ); ?>

<?php if ( $show_bottom_row ) : ?>
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
</div>

<?php

if ( amnesty_feature_is_enabled( 'related-content-posts' ) && 'attachment' !== get_post_type() ) {
	get_template_part( 'partials/single/related-content' );
}
