<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.6.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', [ 'completed', 'processing' ] ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		[
			'downloads'  => $downloads,
			'show_title' => true,
		]
	);
}

?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<h2 class="woocommerce-order-details__title"><?php /* translators: [front] Donate */ esc_html_e( 'Order details', 'woocommerce' ); ?></h2>

	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<thead>
			<tr>
				<th class="woocommerce-table__product-name product-name"><?php /* translators: [front] Donate */ esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="woocommerce-table__product-quantity product-quantity"><?php /* translators: [front] Donate */ esc_html_e( 'Quantity', 'amnesty' ); ?></th>
				<th class="woocommerce-table__product-total product-total"><?php /* translators: [front] Donate */ esc_html_e( 'Price', 'amnesty' ); ?></th>
			</tr>
		</thead>

		<tbody>
		<?php

		do_action( 'woocommerce_order_details_before_order_table_items', $order );

		foreach ( $order_items as $item_id => $item ) {
			$product = $item->get_product();

			wc_get_template(
				'order/order-details-item.php',
				[
					'order'              => $order,
					'item_id'            => $item_id,
					'item'               => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'      => $product ? $product->get_purchase_note() : '',
					'product'            => $product,
				]
			);
		}

		do_action( 'woocommerce_order_details_after_order_table_items', $order );

		?>
		</tbody>

		<tfoot>
		<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
		<?php if ( 'payment_method' === $key ) continue; //phpcs:ignore ?>
			<tr>
				<th scope="row" colspan="2"><?php echo esc_html( $total['label'] ); ?></th>
				<td><?php echo wp_kses_post( $total['value'] ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( $order->get_customer_note() ) : ?>
			<tr>
				<th colspan="2"><?php /* translators: [front] */ esc_html_e( 'Note:', 'woocommerce' ); ?></th>
				<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
			</tr>
		<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', compact( 'order' ) );
}

if ( ! is_order_received_page() ) {
	return;
}

$shop_index = get_option( 'amnesty_woocommerce_options_page' );
$shop_index = isset( $shop_index['shop_index_page'][0] ) ? $shop_index['shop_index_page'][0] : 0;

?>

<section class="woocommerce-order-details">
	<p class="return-to-shop u-textRight">
		<a class="btn" href="<?php echo esc_url( get_permalink( $shop_index ) ); ?>">
			<?php /* translators: [front] Donate */ esc_html_e( 'Return to shop', 'woocommerce' ); ?>
		</a>
	</p>
</section>
