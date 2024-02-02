<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_socials' ) ) {
	/**
	 * Register social sharing options with theme options
	 *
	 * @package Amnesty
	 *
	 * @param CMB2 $options the options object
	 *
	 * @return void
	 */
	function amnesty_register_theme_options_socials( CMB2 $options ): void {
		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Social Media', 'amnesty' ),
				'id'   => '_social_title',
				'type' => 'title',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Facebook URL', 'amnesty' ),
				'id'   => '_social_facebook',
				'type' => 'text',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Twitter Handle', 'amnesty' ),
				'id'   => '_social_twitter',
				'type' => 'text',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Youtube URL', 'amnesty' ),
				'id'   => '_social_youtube',
				'type' => 'text',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Instagram Handle', 'amnesty' ),
				'id'   => '_social_instagram',
				'type' => 'text',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'Telegram Handle', 'amnesty' ),
				'id'   => '_social_telegram',
				'type' => 'text',
			]
		);
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_socials' );
