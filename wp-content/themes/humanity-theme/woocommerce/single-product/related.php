<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $related_products ) {
	return;
}

usort( $related_products, fn ( $a, $b ) => $a->get_date_created() <=> $b->get_date_created() );

$shop_index = get_option( 'amnesty_woocommerce_options_page' );
$shop_index = isset( $shop_index['shop_index_page'][0] ) ? $shop_index['shop_index_page'][0] : 0;

?>

<section class="related products">
	<div class="container">
		<header class="section section--small section--topSpacing postlist-header">
			<h1 class="postlist-headerTitle"><?php /* translators: [front] Donate related products */ esc_html_e( 'Related Items', 'amnesty' ); ?></h1>
			<a class="btn btn--white has-icon" href="<?php echo esc_url( get_permalink( $shop_index ) ); ?>">
				<span class="icon-arrow-left"></span>
				<span><?php /* translators: [front] Donate Label for shop index */ esc_html_e( 'Catalogue', 'amnesty' ); ?></span>
			</a>
		</header>

		<?php

		global $post;

		woocommerce_product_loop_start();


		foreach ( $related_products as $related_product ) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$post = get_post( $related_product->get_id() );

			setup_postdata( $post );

			wc_get_template_part( 'content', 'product' );
		}


		woocommerce_product_loop_end();

		wp_reset_postdata();

		?>
	</div>
</section>
