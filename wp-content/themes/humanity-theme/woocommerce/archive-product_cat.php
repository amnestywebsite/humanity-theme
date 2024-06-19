<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

get_header( 'shop' );


$archive_page = get_option( 'amnesty_woocommerce_options_page' );
$archive_page = isset( $archive_page['category_archive_page'][0] ) ? $archive_page['category_archive_page'][0] : 0;

if ( amnesty_post_has_header( $archive_page ) ) {
	$header_data = amnesty_get_header_data( $archive_page );

	if ( 'amnesty-core/hero' === $header_data['name'] ) {
		echo wp_kses_post( render_hero_block( $header_data['attrs'] ) );
	} else {
		// phpcs:ignore
		echo \Amnesty\Blocks\amnesty_render_header_block( $header_data['attrs'] );
	}
}


remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


do_action( 'woocommerce_before_main_content' );

$product_category = get_queried_object();

$container_aria_label = apply_filters( 'the_title', $product_category->name );
$container_aria_label = format_for_aria_label( $category_title );
$container_aria_label = sprintf( /* translators: [front] Donate %s: product category */ __( 'Products in the %s category', 'amnesty' ), $container_aria_label );

?>

<div class="container">
<?php get_template_part( 'partials/shop/catalogue-header' ); ?>

	<section class="news-section section section--small section--topSpacing" aria-label="<?php echo esc_attr( $container_aria_label ); ?>">
	<?php

	if ( woocommerce_product_loop() ) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action( 'woocommerce_no_products_found' );
	}

	?>
	</section>

<?php get_template_part( 'partials/pagination' ); ?>
</div>

<?php

do_action( 'woocommerce_after_main_content' );

get_footer();
