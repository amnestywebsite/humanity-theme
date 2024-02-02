<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_REST_Request;
use WP_Error;

new Fetch_Menus();

/**
 * Class Amnesty_Fetch_Menus registers and handles the menu route api.
 *
 * @package Amnesty\RestApi
 */
class Fetch_Menus {

	/**
	 * Api route namespace.
	 *
	 * @var string
	 */
	private $namespace = 'amnesty/v1';

	/**
	 * Amnesty_Fetch_Menus constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Validate that a parameter is numeric.
	 *
	 * @param mixed $param - Paramater to check
	 *
	 * @return bool
	 */
	public function validate_numeric( $param ) {
		return is_numeric( $param );
	}

	/**
	 * Register api routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/menu',
			[
				'callback'            => [ $this, 'get_items' ],
				'methods'             => 'GET',
				'permission_callback' => 'is_user_logged_in',
			] 
		);

		register_rest_route(
			$this->namespace,
			'/menu/(?P<id>\d+)',
			[
				'callback'            => [ $this, 'get_item' ],
				'methods'             => 'GET',
				'permission_callback' => 'is_user_logged_in',
				'args'                => [
					'id' => [
						'validate_callback' => [ $this, 'validate_numeric' ],
					],
				],
			] 
		);
	}

	/**
	 * Return all nav menus in WordPress.
	 *
	 * @return array
	 */
	public function get_items() {
		return wp_get_nav_menus();
	}

	/**
	 * Return an array containing the menu object and its rendered html.
	 *
	 * @param \WP_REST_Request $request - Current Request.
	 * @return array|mixed|\WP_REST_Response
	 */
	public function get_item( WP_REST_Request $request ) {
		$object = wp_get_nav_menu_object( $request->get_param( 'id' ) );

		if ( ! $object ) {
			return rest_ensure_response(
				new WP_Error( 'rest_invalid_param', 'Menu item not found', [ 'status' => 404 ] )
			);
		}

		$rendered = wp_nav_menu(
			[
				'menu'            => $request->get_param( 'id' ),
				'container'       => false,
				'container_class' => 'menu-{menu slug}-container',
				'container_id'    => '',
				'menu_class'      => 'menu',
				'menu_id'         => 'category_style_menu',
				'echo'            => false,
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '%3$s',
				'depth'           => 0,
			] 
		);

		return [
			'object'   => $object,
			'rendered' => $rendered,
		];
	}
}
