<?php

/**
 * Title: Post Back Link
 * Description: Output back link to return to item's category archive
 * Slug: amnesty/post-back-link
 * Inserter: no
 */

$show_back_link = ! amnesty_validate_boolish( amnesty_get_option( '_display_category_label' ) );

if ( ! $show_back_link ) {
	return;
}

$main_category = amnesty_get_a_post_term( get_the_ID() );

if ( ! $main_category ) {
	return;
}

?>

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-light"} -->
<div class="wp-block-button is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( amnesty_term_link( $main_category ) ); ?>"><span class="icon-arrow-left"></span>
<span><?php echo esc_html( $main_category->name ); ?></span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
