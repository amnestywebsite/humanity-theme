<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_wc_email_add_image_setting' ) ) {
	/**
	 * Insert a setting into the WC emails section to specify an image
	 * which should appear above the title in email templates.
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array $settings the existing settings fields
	 *
	 * @return array
	 */
	function amnesty_wc_email_add_image_setting( array $settings = [] ): array {
		$insertion_point = array_search( 'woocommerce_email_header_image', array_column( $settings, 'id' ) ) + 1;

		$setting = [
			/* translators: [admin] */
			'title'       => __( 'Title image', 'amnesty' ),
			/* translators: [admin] */
			'desc'        => __( 'URL to an image you want to show above the title. Upload images using the media uploader (Admin > Media).', 'amnesty' ),
			'id'          => 'woocommerce_email_title_image',
			'type'        => 'text',
			'css'         => 'min-width:400px;',
			/* translators: [admin] */
			'placeholder' => __( 'N/A', 'woocommerce' ),
			'default'     => '',
			'autoload'    => false,
			'desc_tip'    => true,
		];

		$settings = array_merge(
			array_slice( $settings, 0, $insertion_point ),
			[ $setting ],
			array_slice( $settings, $insertion_point )
		);

		return $settings;
	}
}

add_filter( 'woocommerce_email_settings', 'amnesty_wc_email_add_image_setting' );

if ( ! function_exists( 'amnesty_wc_email_add_footer_setting' ) ) {
	/**
	 * Insert a setting into the WC emails section to specify an image
	 * which should appear in the footer in email templates.
	 *
	 * @package Amnesty\Plugins\WooCommerce
	 *
	 * @param array $settings the existing settings
	 *
	 * @return array
	 */
	function amnesty_wc_email_add_footer_setting( array $settings = [] ): array {
		$insertion_point = array_search( 'woocommerce_email_footer_text', array_column( $settings, 'id' ) ) + 1;

		$setting = [
			/* translators: [admin] */
			'title'       => __( 'Footer image', 'amnesty' ),
			/* translators: [admin] */
			'desc'        => __( 'URL to an image you want to show in the footer. Upload images using the media uploader (Admin > Media).', 'amnesty' ),
			'id'          => 'woocommerce_email_footer_image',
			'type'        => 'text',
			'css'         => 'min-width:400px;',
			/* translators: [admin] */
			'placeholder' => __( 'N/A', 'woocommerce' ),
			'default'     => '',
			'autoload'    => false,
			'desc_tip'    => true,
		];

		$settings = array_merge(
			array_slice( $settings, 0, $insertion_point ),
			[ $setting ],
			array_slice( $settings, $insertion_point )
		);

		return $settings;
	}
}

add_filter( 'woocommerce_email_settings', 'amnesty_wc_email_add_footer_setting' );
