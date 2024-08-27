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

<a class="btn btn--white has-icon" aria-label="<?php echo esc_attr( sprintf( /* translators: [front] Link to return to all posts of category %s */ __( 'Back to %s', 'amnesty' ), $main_category->name ) ); ?>" href="<?php echo esc_url( amnesty_term_link( $main_category ) ); ?>">
	<span class="icon-arrow-left"></span>
	<span><?php echo esc_html( $main_category->name ); ?></span>
</a>
