<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

remove_all_actions( 'woocommerce_product_tabs' );
remove_all_actions( 'woocommerce_product_after_tabs' );

$the_content = get_post_field( 'post_content' );

if ( ! $the_content ) {
	return;
}

/* translators: [front] Donate product description */
$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) );

?>

<div class="product-description u-cf">
<?php

if ( $heading ) {
	printf( '<h2>%s</h2>', esc_html( $heading ) );
}

the_content();

?>
</div>
