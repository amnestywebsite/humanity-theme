<?php

declare( strict_types = 1 );

// phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh

namespace Amnesty\REST_API;

use WP_Error;
use WP_REST_Server;
use WP_User_Query;

/**
 * Create users for all blogs using this controller
 *
 * @package Amnesty\Users
 */
class Users_Controller extends \WP_REST_Users_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->rest_base = 'aiusers';
	}

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
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			[
				'args'   => [
					'id' => [
						/* translators: [admin] */
						'description' => __( 'Unique identifier for the user.' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'context' => $this->get_context_param( [ 'default' => 'view' ] ),
					],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [
						'force'    => [
							'type'        => 'boolean',
							'default'     => false,
							/* translators: [admin] */
							'description' => __( 'Required to be true, as users do not support trashing.' ),
						],
						'reassign' => [
							'type'              => 'integer',
							/* translators: [admin] */
							'description'       => __( 'Reassign the deleted user\'s posts and links to this user ID.' ),
							'required'          => true,
							'sanitize_callback' => [ $this, 'check_reassign' ],
						],
					],
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Retrieves all users.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {
		// Retrieve the list of registered collection query parameters.
		$registered = $this->get_collection_params();

		/*
		 * This array defines mappings between public API query parameters whose
		 * values are accepted as-passed, and their internal WP_Query parameter
		 * name equivalents (some are the same). Only values which are also
		 * present in $registered will be set.
		 */
		$parameter_mappings = [
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
			'exclude'  => 'exclude',
			'include'  => 'include',
			'order'    => 'order',
			'per_page' => 'number',
			'search'   => 'search',
			'roles'    => 'role__in',
			'slug'     => 'nicename__in',
		];

		$prepared_args = [];

		/*
		 * For each known parameter which is both registered and present in the request,
		 * set the parameter's value on the query $prepared_args.
		 */
		foreach ( $parameter_mappings as $api_param => $wp_param ) {
			if ( isset( $registered[ $api_param ], $request[ $api_param ] ) ) {
				$prepared_args[ $wp_param ] = $request[ $api_param ];
			}
		}

		if ( isset( $registered['offset'] ) && ! empty( $request['offset'] ) ) {
			$prepared_args['offset'] = $request['offset'];
		} else {
			$prepared_args['offset'] = ( $request['page'] - 1 ) * $prepared_args['number'];
		}

		if ( isset( $registered['orderby'] ) ) {
			$orderby_possibles        = [
				'id'              => 'ID',
				'include'         => 'include',
				'name'            => 'display_name',
				'registered_date' => 'registered',
				'slug'            => 'user_nicename',
				'include_slugs'   => 'nicename__in',
				'email'           => 'user_email',
				'url'             => 'user_url',
			];
			$prepared_args['orderby'] = $orderby_possibles[ $request['orderby'] ];
		}

		if ( isset( $registered['who'] ) && ! empty( $request['who'] ) && 'authors' === $request['who'] ) {
			$prepared_args['who'] = 'authors';
		} elseif ( ! current_user_can( 'list_users' ) ) {
			$prepared_args['has_published_posts'] = get_post_types( [ 'show_in_rest' => true ], 'names' );
		}

		if ( ! empty( $prepared_args['search'] ) ) {
			$prepared_args['search'] = '*' . $prepared_args['search'] . '*';
		}
		/**
		 * Filters WP_User_Query arguments when querying users via the REST API.
		 *
		 * @link https://developer.wordpress.org/reference/classes/wp_user_query/
		 *
		 * @since 4.7.0
		 *
		 * @param array           $prepared_args Array of arguments for WP_User_Query.
		 * @param WP_REST_Request $request       The REST API request.
		 */
		$prepared_args = apply_filters( 'rest_user_query', $prepared_args, $request );

		// this is the only line from the parent method that has been changed
		$prepared_args['blog_id'] = 0;

		$query = new WP_User_Query( $prepared_args );

		$users = [];

		foreach ( $query->results as $user ) {
			$data    = $this->prepare_item_for_response( $user, $request );
			$users[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $users );

		// Store pagination values for headers then unset for count query.
		$per_page = (int) $prepared_args['number'];
		$page     = ceil( ( ( (int) $prepared_args['offset'] ) / $per_page ) + 1 );

		$prepared_args['fields'] = 'ID';

		$total_users = $query->get_total();

		if ( $total_users < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $prepared_args['number'], $prepared_args['offset'] );
			$count_query = new WP_User_Query( $prepared_args );
			$total_users = $count_query->get_total();
		}

		$response->header( 'X-WP-Total', (int) $total_users );

		$max_pages = ceil( $total_users / $per_page );

		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$base = add_query_arg( urlencode_deep( $request->get_query_params() ), rest_url( sprintf( '%s/%s', $this->namespace, $this->rest_base ) ) );
		if ( $page > 1 ) {
			$prev_page = $page - 1;

			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}

			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );

			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Get the user, if the ID is valid.
	 *
	 * @param int $id Supplied ID.
	 *
	 * @return WP_User|WP_Error True if ID is valid, WP_Error otherwise.
	 */
	protected function get_user( $id ) {
		$error = new WP_Error(
			'rest_user_invalid_id',
			/* translators: [admin] */
			__( 'Invalid user ID.' ),
			[ 'status' => 404 ]
		);

		if ( (int) $id <= 0 ) {
			return $error;
		}

		$user = get_userdata( (int) $id );
		if ( empty( $user ) || ! $user->exists() ) {
			return $error;
		}

		// multisite & member of blog check removed

		return $user;
	}

	/**
	 * Creates a single user.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function create_item( $request ) {
		if ( ! is_multisite() ) {
			return parent::create_item( $request );
		}

		if ( ! empty( $request['id'] ) ) {
			return new WP_Error(
				'rest_user_exists',
				/* translators: [admin] */
				__( 'Cannot create existing user.' ),
				[ 'status' => 400 ]
			);
		}

		$schema = $this->get_item_schema();

		if ( ! empty( $request['roles'] ) && ! empty( $schema['properties']['roles'] ) ) {
			$check_permission = $this->check_role_update( $request['id'], $request['roles'] );

			if ( is_wp_error( $check_permission ) ) {
				return $check_permission;
			}
		}

		$user = $this->prepare_item_for_database( $request );

		$ret = wpmu_validate_user_signup( $user->user_login, $user->user_email );

		if ( is_wp_error( $ret['errors'] ) && $ret['errors']->has_errors() ) {
			$error = new WP_Error(
				'rest_invalid_param',
				/* translators: [admin] */
				__( 'Invalid user parameter(s).' ),
				[ 'status' => 400 ]
			);

			foreach ( $ret['errors']->errors as $code => $messages ) {
				foreach ( $messages as $message ) {
					$error->add( $code, $message );
				}

				$error_data = $error->get_error_data( $code );

				if ( $error_data ) {
					$error->add_data( $error_data, $code );
				}
			}
			return $error;
		}

		$user_id = wpmu_create_user( $user->user_login, $user->user_pass, $user->user_email );

		if ( ! $user_id ) {
			return new WP_Error(
				'rest_user_create',
				/* translators: [admin] */
				__( 'Error creating new user.' ),
				[ 'status' => 500 ]
			);
		}

		$user->ID = $user_id;
		$user_id  = wp_update_user( wp_slash( (array) $user ) );

		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		// this bit has been changed from the parent method
		foreach ( get_sites() as $site ) {
			$result = add_user_to_blog( $site->blog_id, $user_id, empty( $request['roles'][0] ) ? '' : $request['roles'][0] );

			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}

		$user = get_user_by( 'id', $user_id );

		/**
		 * Fires immediately after a user is created or updated via the REST API.
		 *
		 * @since 4.7.0
		 *
		 * @param WP_User         $user     Inserted or updated user object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating True when creating a user, false when updating.
		 */
		do_action( 'rest_insert_user', $user, $request, true );

		if ( ! empty( $request['roles'] ) && ! empty( $schema['properties']['roles'] ) ) {
			array_map( [ $user, 'add_role' ], $request['roles'] );
		}

		if ( ! empty( $schema['properties']['meta'] ) && isset( $request['meta'] ) ) {
			$meta_update = $this->meta->update_value( $request['meta'], $user_id );

			if ( is_wp_error( $meta_update ) ) {
				return $meta_update;
			}
		}

		$user          = get_user_by( 'id', $user_id );
		$fields_update = $this->update_additional_fields_for_object( $user, $request );

		if ( is_wp_error( $fields_update ) ) {
			return $fields_update;
		}

		$request->set_param( 'context', 'edit' );

		/**
		 * Fires after a user is completely created or updated via the REST API.
		 *
		 * @since 5.0.0
		 *
		 * @param WP_User         $user     Inserted or updated user object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating True when creating a user, false when updating.
		 */
		do_action( 'rest_after_insert_user', $user, $request, true );

		$response = $this->prepare_item_for_response( $user, $request );
		$response = rest_ensure_response( $response );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $user_id ) ) );

		return $response;
	}

}
