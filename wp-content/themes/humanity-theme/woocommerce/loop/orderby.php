<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name = 'woo-orderby';

// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.WP.GlobalVariablesOverride.Prohibited
$orderby = sanitize_text_field( $_GET['orderby'] ?? 'menu_order' );
$options = $catalog_orderby_options;

/* translators: [front] Donate Label for post sorting options */
$field_label = __( 'Sort by', 'amnesty' );

amnesty_render_custom_select(
	[
		'label'      => $field_label,
		'show_label' => true,
		'name'       => $name,
		'is_form'    => true,
		'multiple'   => false,
		'options'    => $options,
		'active'     => $orderby,
	]
);
