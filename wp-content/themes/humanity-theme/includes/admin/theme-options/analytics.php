<?php

// phpcs:disable PEAR.Commenting.InlineComment.WrongStyle,Squiz.Commenting.InlineComment.WrongStyle

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_analytics_options' ) ) {
	/**
	 * Register analytics options
	 *
	 * @package Amnesty
	 *
	 * @return void
	 */
	function amnesty_register_theme_analytics_options(): void {
		$analytics = new_cmb2_box(
			[
				'id'           => 'amnesty_analytics_options_page',
				'option_key'   => 'amnesty_analytics_options_page',
				/* translators: [admin] */
				'title'        => __( 'Analytics', 'amnesty' ),
				'object_types' => [ 'options-page' ],
				'tab_group'    => 'amnesty_theme_options',
				/* translators: [admin] */
				'tab_title'    => __( 'Analytics', 'amnesty' ),
				'parent_slug'  => 'amnesty_theme_options_page',
				'display_cb'   => 'amnesty_options_display_with_tabs',
			]
		);

		#region basic fields

		$analytics->add_field(
			[
				'id'   => 'gtm_code',
				/* translators: [admin] */
				'name' => __( 'Google Tag Manager Code', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Paste your Google Tag Manager Code', 'amnesty' ),
				'type' => 'text',
			]
		);

		$analytics->add_field(
			[
				'id'      => 'gtm_consent_mode',
				/* translators: [admin] */
				'name'    => __( 'GTM Consent Mode', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( "The status, and default value of, Google's Consent Mode (see https://support.google.com/analytics/answer/9976101). Note that, when using Consent Mode, you will have to implement targeting via a cookie control plugin such as Civic", 'amnesty' ),
				'type'    => 'select',
				'default' => 'inactive',
				'options' => [
					'inactive' => __( 'Not Enabled', 'amnesty' ),
					'denied'   => __( 'Enabled, denied', 'amnesty' ),
					'granted'  => __( 'Enabled, granted', 'amnesty' ),
				],
			]
		);

		$analytics->add_field(
			[
				'id'   => '_analytics_code',
				/* translators: [admin] */
				'name' => __( 'Google Analytics Code', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Paste your Google Analytics Code', 'amnesty' ),
				'type' => 'text',
			]
		);

		$analytics->add_field(
			[
				'id'   => '_analytics_4_code',
				/* translators: [admin] */
				'name' => __( 'Google Analytics v4 Code', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Paste your Google Analytics v4 Code; you must also have entered your GA Code above', 'amnesty' ),
				'type' => 'text',
			]
		);

		$analytics->add_field(
			[
				'id'   => '_analytics_hotjar',
				/* translators: [admin] */
				'name' => __( 'Hotjar Site ID:', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Paste your Hotjar Site ID (example: 552474)', 'amnesty' ),
				'type' => 'text',
			]
		);

		$analytics->add_field(
			[
				'id'   => '_analytics_vwo',
				/* translators: [admin] */
				'name' => __( 'VWO Account ID', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Paste your VWO Account ID here', 'amnesty' ),
				'type' => 'text',
			]
		);

		#endregion basic fields

		#region meta fields

		$analytics->add_field(
			[
				'id'   => 'meta_title',
				'type' => 'title',
				/* translators: [admin] */
				'name' => __( 'Site Meta Tags', 'amnesty' ),
			]
		);

		$meta = $analytics->add_field(
			[
				'id'         => 'meta',
				'type'       => 'group',
				/* translators: [admin] */
				'name'       => __( 'Meta Tags', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => '<strong>' . __( 'Add your site meta tags (&lt;meta name="thing" /&gt;) here; useful for things like site verification tags', 'amnesty' ) . '</strong><br>' . __( 'If using WordPress SEO (Yoast) or similar, you may wish to use their built-in alternatives', 'amnesty' ),
				'repeatable' => true,
				'options'    => [
					/* translators: [admin] */
					'add_button'    => __( 'Add Tag', 'amnesty' ),
					/* translators: [admin] */
					'remove_button' => __( 'Remove Tag', 'amnesty' ),
					'sortable'      => false,
				],
			]
		);

		$analytics->add_group_field(
			$meta,
			[
				'id'      => 'key',
				'type'    => 'text',
				/* translators: [admin] */
				'name'    => __( 'Meta Name', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'e.g. google-site-verification', 'amnesty' ),
				'classes' => 'ai-cmb-inline',
			]
		);

		$analytics->add_group_field(
			$meta,
			[
				'id'      => 'value',
				'type'    => 'text',
				/* translators: [admin] */
				'name'    => __( 'Meta Value', 'amnesty' ),
				'desc'    => 'e.g. GAMXRoD3BC1f8wp7ySpDOmohGFPVAd5C',
				'classes' => 'ai-cmb-inline',
			]
		);

		$rdf = $analytics->add_field(
			[
				'id'         => 'rdf',
				'type'       => 'group',
				'name'       => __( 'RDF Tags', 'amnesty' ),
				'desc'       => '<strong>' . __( 'Add your site rdf tags (&lt;meta property="thing" /&gt;) here; useful for things like Facebook pages', 'amnesty' ) . '</strong><br>' . __( 'If using WordPress SEO (Yoast) or similar, you may wish to use their built-in alternatives', 'amnesty' ),
				'repeatable' => true,
				'options'    => [
					/* translators: [admin] */
					'add_button'    => __( 'Add Property', 'amnesty' ),
					/* translators: [admin] */
					'remove_button' => __( 'Remove Property', 'amnesty' ),
					'sortable'      => false,
				],
			]
		);

		$analytics->add_group_field(
			$rdf,
			[
				'id'      => 'key',
				'type'    => 'text',
				/* translators: [admin] */
				'name'    => __( 'Meta Property', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'e.g. fb:pages', 'amnesty' ),
				'classes' => 'ai-cmb-inline',
			]
		);

		$analytics->add_group_field(
			$rdf,
			[
				'id'      => 'value',
				'type'    => 'text',
				/* translators: [admin] */
				'name'    => __( 'Meta Value', 'amnesty' ),
				'desc'    => 'e.g. GAMXRoD3BC1f8wp7ySpDOmohGFPVAd5C',
				'classes' => 'ai-cmb-inline',
			]
		);

		#endregion meta fields

		#region deprecated
		$gtm_deprecated = amnesty_get_option( '_analytics_script', 'amnesty_analytics_options_page' );

		if ( $gtm_deprecated ) {
			$analytics->add_field(
				[
					'id'   => 'deprecated_title',
					'type' => 'title',
					/* translators: [admin] */
					'name' => __( 'Deprecated fields', 'amnesty' ),
				]
			);

			$analytics->add_field(
				[
					'id'      => 'deprecated',
					'type'    => 'message',
					/* translators: [admin] */
					'message' => __( 'The below fields are deprecated. Avoid using them if they are not currently in use on this site', 'amnesty' ),
				]
			);

			$analytics->add_field(
				[
					'id'   => '_analytics_script',
					/* translators: [admin] */
					'name' => __( 'Google Tag Manager Script', 'amnesty' ),
					/* translators: [admin] */
					'desc' => __( 'Deprecated. Do not use unless already in use.', 'amnesty' ),
					'type' => 'textarea_code',
				]
			);

			$analytics->add_field(
				[
					'id'   => '_analytics_noscript',
					/* translators: [admin] */
					'name' => __( 'Google Tag Manager Noscript', 'amnesty' ),
					/* translators: [admin] */
					'desc' => __( 'Deprecated. Do not use unless already in use.', 'amnesty' ),
					'type' => 'textarea_code',
				]
			);
		}
		#endregion deprecated
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_analytics_options' );
