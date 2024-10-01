<?php

/**
 * Title: Post Share
 * Description: Output article sharing links
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
<span>Campaigns</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
