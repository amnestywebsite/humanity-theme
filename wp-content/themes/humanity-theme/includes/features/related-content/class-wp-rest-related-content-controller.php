<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_REST_Controller;
use WP_REST_Server;

add_action(
	'rest_api_init',
	function (): void {
		$controller = new WP_REST_Related_Content_Controller();
		$controller->register_routes();
	} 
);

/**
 * The API controller class for Related Content
 *
 * @package Amnesty\Features
 */
class WP_REST_Related_Content_Controller extends WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'amnesty/v1';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base = 'related/(?P<id>[\d]+)';

	/**
	 * Cached results of get_item_schema.
	 *
	 * @var array
	 */
	protected $schema;

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				'schema' => [ $this, 'get_public_item_schema' ],
				'args'   => [
					'id' => [
						'description' => __( 'Unique identifier for the post.' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_args_for_request(),
				],
			] 
		);
	}

	/**
	 * Checks if a given request has access to get items.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return true|\WP_Error True if the request has read access, WP_Error object otherwise.
	 *
	 * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
	 */
	public function get_items_permissions_check( $request ) {
		$post = get_post( $request['id'] );

		if ( is_wp_error( $post ) ) {
			return $post;
		}

		$post_type = get_post_type_object( $post->post_type );
		if ( ! $this->check_is_post_type_allowed( $post_type ) ) {
			return false;
		}

		// Is the post readable?
		if ( 'publish' === $post->post_status || current_user_can( 'read_post', $post->ID ) ) {
			return true;
		}

		$post_status_obj = get_post_status_object( $post->post_status );
		if ( $post_status_obj && $post_status_obj->public ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieves a collection of items.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_REST_Response|\WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		$post = get_post( absint( $request['id'] ) );

		if ( is_wp_error( $post ) ) {
			return $post;
		}

		$related = new Related_Content( false, $post->ID );

		$taxonomies = [];

		foreach ( array_keys( $this->get_args_for_request() ) as $taxonomy ) {
			if ( ! isset( $request[ $taxonomy ] ) ) {
				continue;
			}

			$taxonomies[ $taxonomy ] = $request[ $taxonomy ];
		}

		$response = $related->get_api_data( $taxonomies );

		return rest_ensure_response( $response );
	}

	/**
	 * Retrieves the item's schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => __( 'Related Content', 'amnesty' ),
			'type'       => 'object',
			'properties' => [
				'id'      => [
					'description' => __( 'Unique identifier for the post.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
				'type'    => [
					'description' => __( 'Type of post.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
				'link'    => [
					'description' => __( 'URL to the post.' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
				'title'   => [
					'description' => __( 'HTML title for the post, transformed for display.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
				'excerpt' => [
					'description' => __( 'HTML excerpt for the post, transformed for display.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
				'feature' => [
					// translators: [API/admin]
					'description' => __( 'The featured image for the post.', 'amnesty' ),
					'type'        => 'string',
					'context'     => [ 'view', 'embed' ],
					'readonly'    => true,
				],
			],
		];

		return $this->schema;
	}

	/**
	 * Checks if a given post type can be viewed or managed.
	 *
	 * @param WP_Post_Type|string $post_type Post type name or object.
	 *
	 * @return bool Whether the post type is allowed in REST.
	 */
	protected function check_is_post_type_allowed( $post_type ) {
		if ( ! is_object( $post_type ) ) {
			$post_type = get_post_type_object( $post_type );
		}

		if ( ! empty( $post_type ) && ! empty( $post_type->show_in_rest ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Build the allowed arguments for the request
	 *
	 * @return array<string,array<string,mixed>>
	 */
	protected function get_args_for_request(): array {
		$args = [];

		$taxonomies = get_taxonomies(
			[
				'amnesty' => true,
				'public'  => true,
			],
			'objects' 
		);

		$taxonomies = apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );

		foreach ( $taxonomies as $taxonomy ) {
			$args[ $taxonomy->name ] = [
				// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
				'description' => sprintf( __( 'The terms assigned to the post in the %s taxonomy.' ), $taxonomy->name ),
				'type'        => 'array',
				'items'       => [
					'type' => 'integer',
				],
				'context'     => [ 'view', 'edit' ],
			];
		}

		return $args;
	}

}
