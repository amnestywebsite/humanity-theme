<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_rest_api_allow_writing_post_modified' ) ) {
	/**
	 * Allow post modification date to be set when creating a post via the REST API
	 *
	 * @package Amnesty\RestApi
	 *
	 * @param array<string,mixed> $schema the item schema
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_rest_api_allow_writing_post_modified( array $schema ): array {
		$schema['properties']['modified']['readonly']     = false;
		$schema['properties']['modified_gmt']['readonly'] = false;
		return $schema;
	}
}

// allow post modification date to be set when creating a post in the REST API
add_filter( 'rest_post_item_schema', 'amnesty_rest_api_allow_writing_post_modified' );

if ( ! function_exists( 'amnesty_rest_api_register_extra_meta' ) ) {
	/**
	 * Register additional meta fields for posts in the REST API
	 *
	 * @package Amnesty\RestApi
	 *
	 * @return void
	 */
	function amnesty_rest_api_register_extra_meta(): void {
		// register field for storing original Umbraco data (primarily for diagnostics)
		register_meta(
			'post',
			'amnesty_umbraco_data',
			[
				/* translators: [admin] */
				'description'    => __( 'Raw Umbraco data for this item', 'amnesty' ),
				'type'           => 'string',
				'object_subtype' => 'post',
				'single'         => true,
				'show_in_rest'   => true,
			]
		);
	}
}

add_action( 'init', 'amnesty_rest_api_register_extra_meta' );

if ( ! function_exists( 'amnesty_rest_api_cache_oembeds' ) ) {
	/**
	 * Process oembed data for posts created via the REST API
	 *
	 * @package Amnesty\RestApi
	 *
	 * @param int $post_id the created post's ID
	 *
	 * @return void
	 */
	function amnesty_rest_api_cache_oembeds( int $post_id ): void {
		// only target rest requests
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			return;
		}

		// that haven't come from the admin
		if ( wp_get_referer() && false !== strpos( wp_get_referer(), 'wp-admin' ) ) {
			return;
		}

		/**
		 * WP's global oembed handler
		 *
		 * @var \WP_Embed $wp_embed
		 */
		global $wp_embed;

		// cache any oembeds for the psot
		$wp_embed->cache_oembed( $post_id );
	}
}

// process oembed data for posts created through the REST API
add_action( 'save_post_post', 'amnesty_rest_api_cache_oembeds' );
