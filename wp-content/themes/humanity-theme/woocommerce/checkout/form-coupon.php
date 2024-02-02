<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) {
	return;
}

/* translators: [front] Donate */
$coupon_message = esc_html__( 'Have a coupon?', 'woocommerce' );
/* translators: [front] Donate */
$coupon_message = $coupon_message . sprintf( '<a class="showcoupon" href="#">%s</a>', esc_html__( 'Click here to enter your code', 'woocommerce' ) );
$coupon_message = apply_filters( 'woocommerce_checkout_coupon_message', $coupon_message );

?>
<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( $coupon_message, 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">
	<p><?php /* translators: [front] Donate */ esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></p>

	<p>
		<input id="coupon_code" class="input-text input-narrow" type="text" name="coupon_code" placeholder="<?php /* translators: [front] Donate */ esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" value="">
		<button class="btn" type="submit" name="apply_coupon" value="<?php /* translators: [front] */ esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php /* translators: [front] Donate */ esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
	</p>
</form>
