<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_woocommerce' ) ) {
	/**
	 * Register theme options for WooCommerce
	 *
	 * @package Amnesty\WooCommerce
	 *
	 * @return void
	 */
	function amnesty_register_theme_options_woocommerce(): void {
		$woocommerce = new_cmb2_box(
			[
				'id'           => 'amnesty_woocommerce_options_page',
				'option_key'   => 'amnesty_woocommerce_options_page',
				'title'        => 'WooCommerce',
				'object_types' => [ 'options-page' ],
				'tab_group'    => 'amnesty_theme_options',
				'tab_title'    => 'WooCommerce',
				'parent_slug'  => 'amnesty_theme_options_page',
				'display_cb'   => 'amnesty_options_display_with_tabs',
			]
		);

		$woocommerce->add_field(
			[
				'id'      => 'shop_index_page',
				/* translators: [admin] */
				'name'    => __( 'Shop Index Page', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Select the page used for the Shop Index', 'amnesty' ),
				'type'    => 'custom_attached_posts',
				'options' => [
					'show_thumbnails' => false,
					'filter_boxes'    => false,
					'query_args'      => [
						'posts_per_page' => 10,
						'post_type'      => 'page',
					],
				],
			]
		);

		$woocommerce->add_field(
			[
				'id'      => 'category_archive_page',
				/* translators: [admin] */
				'name'    => __( 'Category Archive Page', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Select the page used for the Product Category Archive', 'amnesty' ),
				'type'    => 'custom_attached_posts',
				'options' => [
					'show_thumbnails' => false,
					'filter_boxes'    => false,
					'query_args'      => [
						'posts_per_page' => 10,
						'post_type'      => 'page',
					],
				],
			]
		);
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_woocommerce' );
