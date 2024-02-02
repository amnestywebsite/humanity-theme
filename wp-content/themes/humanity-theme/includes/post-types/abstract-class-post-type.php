<?php

declare( strict_types = 1 );

namespace Amnesty;

/**
 * Base post type declaration
 *
 * @package Amnesty\PostTypes
 */
abstract class Post_Type {

	/**
	 * Option key for this post type
	 *
	 * @var string
	 */
	protected $key = '';

	/**
	 * Post type name - source language slug
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Post type slug - target language slug
	 *
	 * @var string
	 */
	protected $slug = '';

	/**
	 * Post type registration params
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Whether the post type is enabled by default
	 *
	 * @var bool
	 */
	protected $default_enabled = true;

	/**
	 * Add hooks
	 */
	public function __construct() {
		$this->name = sanitize_title_with_dashes( $this->name );
		$this->key  = sprintf( 'amnesty_%s', $this->name );
		$this->slug = sanitize_title_with_dashes( get_option( "{$this->key}_slug" ) ?: $this->slug );

		add_action( 'amnesty_register_post_type', [ $this, 'declare' ], 10, 2 );

		if ( ! $this->is_enabled() ) {
			return;
		}

		add_action( 'init', [ $this, 'register' ] );
		add_action( 'cmb2_admin_init', [ $this, 'metabox' ] );
		add_action( 'amnesty_permalink_settings', [ $this, 'add_settings' ] );
		add_action( 'amnesty_permalink_settings_save', [ $this, 'save_settings' ] );
	}

	/**
	 * Declare the post type description
	 *
	 * @return string
	 */
	abstract protected function desc();

	/**
	 * Declare the post type labels
	 *
	 * @return array
	 */
	abstract protected function labels();

	/**
	 * Declare this post type for feature support configuration
	 *
	 * @param mixed  $cmb2  the post type options metabox
	 * @param string $group the post type options group
	 *
	 * @return void
	 */
	public function declare( $cmb2, $group ): void {
		$cmb2->add_group_field(
			$group,
			[
				'id'      => $this->slug,
				'name'    => $this->labels()['name'],
				'type'    => 'checkbox',
				'default' => $this->default_enabled,
			]
		);
	}

	/**
	 * Register the post type
	 *
	 * @return void
	 */
	public function register() {
		if ( ! $this->slug ) {
			return;
		}

		register_post_type( $this->slug, $this->args() );
	}

	/**
	 * Register the post type custom fields, if any
	 *
	 * @return void
	 */
	public function metabox() {
	}

	/**
	 * Register post type slug setting for localisation
	 *
	 * @return void
	 */
	public function add_settings() {
		require locate_template( 'partials/admin/permalinks-post-type.php' );
	}

	/**
	 * Save the localised post type slug
	 *
	 * @param array $data the POSTed data
	 *
	 * @return void
	 */
	public function save_settings( array $data = [] ) {
		$old_slug = get_option( "{$this->key}_slug" ) ?: $this->name;

		if ( ! empty( $data[ "{$this->key}_slug" ] ) ) {
			$this->slug = sanitize_title_with_dashes( $data[ "{$this->key}_slug" ] );
			update_option( "{$this->key}_slug", $this->slug );
		}

		if ( ! empty( $data[ "{$this->key}_hierarchical" ] ) ) {
			update_option( "{$this->key}_hierarchical", $data[ "{$this->key}_hierarchical" ] );
		} else {
			update_option( "{$this->key}_hierarchical", 'off' );
		}

		if ( $this->slug !== $old_slug ) {
			$this->update_db( $old_slug );
		}
	}

	/**
	 * Parse and fill post type registration args
	 *
	 * @return array
	 */
	protected function args() {
		$args = array_merge(
			$this->args,
			[
				'labels'   => $this->labels(),
				'desc'     => $this->desc(),
				'codename' => $this->name,
			]
		);

		$args = wp_parse_args(
			$args,
			[
				'hierarchical' => false,
				'rewrite'      => true,
			]
		);

		// no rewrite rules, no config required
		if ( false === $args['rewrite'] ) {
			return $args;
		}

		if ( ! is_array( $args['rewrite'] ) ) {
			$args['rewrite'] = [];
		}

		$hierarchical = amnesty_validate_boolish( get_option( "{$this->key}_hierarchical", null ), true );

		// ensure localised slug used for rewrite rules
		$args['rewrite'] = wp_parse_args(
			$args['rewrite'],
			[
				'ep_mask'      => EP_NONE,
				'slug'         => $this->slug,
				'hierarchical' => $hierarchical,
				'with_front'   => true,
			]
		);

		return $args;
	}

	/**
	 * Default post slug has changed - update the database accordingly
	 *
	 * @param string $old_slug the previous slug
	 *
	 * @global $wpdb
	 *
	 * @return void
	 */
	protected function update_db( string $old_slug ) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->update( $wpdb->posts, [ 'post_type' => $this->slug ], [ 'post_type' => $old_slug ] );
	}

	/**
	 * Whether the post type is enabled globally
	 *
	 * @return bool
	 */
	protected function is_enabled() {
		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_post_types'][0][ $this->name ] ) ) {
			return $this->default_enabled;
		}

		return amnesty_validate_boolish( $options['enabled_post_types'][0][ $this->name ] );
	}

}
