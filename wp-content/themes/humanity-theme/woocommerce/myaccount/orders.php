<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );


if ( $has_orders ) {
	include locate_template( 'partials/myaccount/orders.php' );
	return;
}

?>

<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
	<?php /* translators: [front] Donate */ esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
</div>
<div class="u-textCenter">
	<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
		<?php /* translators: [front] Donate Shop */ esc_html_e( 'Browse products', 'woocommerce' ); ?>
	</a>
</div>

<?php

do_action( 'woocommerce_after_account_orders', $has_orders );
