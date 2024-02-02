<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

$shop_index = get_option( 'amnesty_woocommerce_options_page' );
$shop_index = isset( $shop_index['shop_index_page'][0] ) ? $shop_index['shop_index_page'][0] : 0;

?>

<p class="return-to-shop u-textCenter">
	<a class="button wc-backward" href="<?php echo esc_url( get_permalink( $shop_index ) ); ?>">
		<?php /* translators: [front] Donate */ esc_html_e( 'Return to shop', 'woocommerce' ); ?>
	</a>
</p>
