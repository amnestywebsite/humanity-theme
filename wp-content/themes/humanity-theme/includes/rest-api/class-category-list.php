<?php

declare( strict_types = 1 );

namespace Amnesty;

new Category_List();

/**
 * Register the category list API endpoint
 *
 * @package Amnesty\RestApi
 */
class Category_List {

	/**
	 * Found categories store
	 *
	 * @var array
	 */
	protected $results = [];

	/**
	 * Add hook(s)
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	/**
	 * Register the route(s)
	 *
	 * @return void
	 */
	public function register() {
		register_rest_route(
			'amnesty/v1',
			'categories',
			[
				'methods'             => [ 'GET' ],
				'callback'            => [ $this, 'get' ],
				'permission_callback' => 'is_user_logged_in',
			]
		);
	}

	/**
	 * Process a request
	 *
	 * @return array
	 */
	public function get() {
		$this->results = get_terms(
			[
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'number'     => 0,
			]
		);

		return $this->sort();
	}

	/**
	 * Sort a list of items
	 *
	 * @param integer $parent_id the parent ID
	 *
	 * @return array
	 */
	protected function sort( $parent_id = 0 ) {
		$kids = array_values(
			array_filter(
				$this->results,
				function ( $item ) use ( $parent_id ) {
					return $item->parent === $parent_id;
				}
			)
		);

		$sorted = [];

		foreach ( $kids as $kid ) {
			$sorted[] = $kid;
			$sorted   = array_merge( $sorted, $this->sort( $kid->term_id ) );
		}

		return $sorted;
	}

}
