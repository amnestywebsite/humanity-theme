<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_user_meta' ) ) {
	/**
	 * Register meta fields for users
	 *
	 * @package Amnesty
	 *
	 * @return void
	 */
	function amnesty_register_user_meta(): void {
		register_meta(
			'user',
			'amnesty_umbraco_info',
			[
				/* translators: [admin] */
				'description'  => __( 'Amnesty Umbraco User Info', 'amnesty' ),
				'type'         => 'string',
				'single'       => true,
				'default'      => '',
				'show_in_rest' => true,
			]
		);
	}
}

// record the original user info from umbraco
add_action( 'init', 'amnesty_register_user_meta' );
