<?php

declare( strict_types = 1 );

use Amnesty\REST_API\Users_Controller;

if ( ! function_exists( 'amnesty_register_users_controller' ) ) {
	/**
	 * Regiser a new users controller with the REST API
	 *
	 * @package Amnesty\RestApi
	 *
	 * @return void
	 */
	function amnesty_register_users_controller(): void {
		if ( ! class_exists( '\\Amnesty\\REST_API\\Users_Controller' ) ) {
			return;
		}

		$controller = new Users_Controller();
		$controller->register_routes();
	}
}

add_action( 'rest_api_init', 'amnesty_register_users_controller' );

if ( ! function_exists( 'amnesty_register_rest_field_user_blogs' ) ) {
	/**
	 * Register user's blogs field with the REST API
	 *
	 * @package Amnesty\RestApi
	 *
	 * @return void
	 */
	function amnesty_register_rest_field_user_blogs(): void {
		register_rest_field(
			'user',
			'blogs',
			[
				'get_callback' => function ( array $user ): array {
					$blogs = get_blogs_of_user( absint( $user['id'] ), true );
					return array_map( 'absint', array_keys( $blogs ) );
				},
			]
		);
	}
}

add_action( 'rest_api_init', 'amnesty_register_rest_field_user_blogs' );

if ( ! function_exists( 'amnesty_rest_api_restricted_endpoints' ) ) {
	/**
	 * Restrict certain API endpoints to logged-in users
	 *
	 * @package Amnesty\RestApi
	 *
	 * @param mixed           $response the API response data
	 * @param WP_REST_Server  $server   the REST API server object
	 * @param WP_REST_Request $request  the REST API request object
	 *
	 * @return mixed
	 */
	function amnesty_rest_api_restricted_endpoints( mixed $response, WP_REST_Server $server, WP_REST_Request $request ): mixed {
		$denied_endpoints = [
			'/wp/v2/users',
			'/wp/v2/aiusers',
			'/wp/v2/media-contact',
		];

		// restrict only certain endpoints
		if ( ! in_array( $request->get_route(), $denied_endpoints, true ) ) {
			return $response;
		}

		// let normal user authentication continue
		if ( is_user_logged_in() ) {
			return $response;
		}

		return new WP_Error(
			'rest_forbidden',
			__( 'Sorry, you are not allowed to do that.', 'amnesty' ),
			[ 'status' => rest_authorization_required_code() ],
		);
	}
}

add_filter( 'rest_pre_dispatch', 'amnesty_rest_api_restricted_endpoints', 100, 3 );
