<?php

/**
 * WooCommerce partial, order customer details
 *
 * @package Amnesty\Plugins\WooCommerce\Partials
 */

?>
<section class="woocommerce-customer-details">
	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">
			<h3 class="woocommerce-column__title"><?php /* translators: [front] Donate */ esc_html_e( 'Customer Details', 'amnesty' ); ?></h3>
			<div>
				<dl>
				<?php if ( $order->get_billing_email() ) : ?>
					<dt><?php /* translators: [front] Donate forms */ esc_html_e( 'Email:', 'amnesty' ); ?></dt>
					<dd><?php echo esc_html( $order->get_billing_email() ); ?><?php echo esc_html( $order->get_billing_phone() ); ?></dd>
				<?php endif; ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<dt><?php /* translators: [front] Donate forms */ esc_html_e( 'Tel:', 'amnesty' ); ?></dt>
					<dd><?php echo esc_html( $order->get_billing_phone() ); ?></dd>
				<?php endif; ?>
				</dl>
			</div>
		</div>
		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
			<h3 class="woocommerce-column__title"><?php /* translators: [front] Donate forms */ esc_html_e( 'Payment Method', 'amnesty' ); ?></h3>
			<div><?php echo esc_html( $order->get_order_item_totals()['payment_method']['value'] ); ?></div>
		</div>
	</section>

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">
			<h3 class="woocommerce-column__title"><?php /* translators: [front] Donate forms */ esc_html_e( 'Billing address', 'woocommerce' ); ?></h3>
			<address>
			<?php echo wp_kses_post( $order->get_formatted_billing_address( /* translators: [front] Donate forms */ esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
			</address>
		</div>

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
			<h3 class="woocommerce-column__title"><?php /* translators: [front] Donate forms */ esc_html_e( 'Shipping address', 'woocommerce' ); ?></h3>
			<address>
			<?php echo wp_kses_post( $order->get_formatted_shipping_address( /* translators: [front] Donate forms */ esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
				<?php if ( $order->get_shipping_phone() ) : ?>
					<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
				<?php endif; ?>
			</address>
		</div>
	</section>

<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</section>
