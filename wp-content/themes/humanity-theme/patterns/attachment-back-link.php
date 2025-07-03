<?php

/**
 * Title: Attachment Back Link
 * Description: Output back link to return to item's category archive
 * Slug: amnesty/attachment-back-link
 * Inserter: no
 */

use function Inpsyde\MultilingualPress\translationIds;

global $post;

$should_switch_blog = ! empty( $post->blog_id ) && absint( $post->blog_id ) !== absint( get_current_blog_id() );

if ( $should_switch_blog ) {
	switch_to_blog( $post->blog_id );
}

$show_back_link = ! amnesty_validate_boolish( amnesty_get_option( '_display_category_label' ) );
$main_category  = amnesty_get_a_post_term( get_the_ID() );

if ( ! $main_category ) {
	return;
}

if ( $should_switch_blog ) {
	restore_current_blog();
}

$relations = translationIds( $main_category->term_id, 'term', $post?->blog_id ?? 0 );

if ( isset( $relations[ get_current_blog_id() ] ) ) {
	$main_category = get_term( $relations[ get_current_blog_id() ], $main_category->taxonomy );
}

if ( ! $show_back_link || ! is_a( $main_category, WP_Term::class ) ) {
	return;
}

?>

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-light"} -->
<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( amnesty_term_link( $main_category ) ); ?>"><span class="icon-arrow-left"></span>
<span><?php echo esc_html( $main_category->name ); ?></span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
