<?php

/**
 * WooCommerce partial, catalogue header
 *
 * @package Amnesty\Partials
 */

$shop_index = get_option( 'amnesty_woocommerce_options_page' );
$shop_index = isset( $shop_index['shop_index_page'][0] ) ? $shop_index['shop_index_page'][0] : 0;

/* translators: [front] Label for category archive navigation */
$aria_label = __( 'Product information', 'amnesty' );
$main_title = apply_filters( 'the_title', get_queried_object()->name );
if ( is_post_type_archive() ) {
	/* translators: [front] */
	$aria_label = __( 'All products', 'amnesty' );
	$main_title = $aria_label;
}

?>
<header class="section section--small section--topSpacing postlist-header" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<a class="btn btn--white has-icon" href="<?php echo esc_url( get_permalink( $shop_index ) ); ?>">
		<span class="icon-arrow-left"></span>
		<span><?php /* translators: [front] Label for shop index */ esc_html_e( 'Catalogue', 'amnesty' ); ?></span>
	</a>
	<h2 class="postlist-headerTitle"><?php echo esc_html( $main_title ); ?></h2>

	<?php woocommerce_catalog_ordering(); ?>
</header>
