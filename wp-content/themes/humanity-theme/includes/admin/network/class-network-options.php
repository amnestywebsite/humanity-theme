<?php

declare( strict_types = 1 );

namespace Amnesty;

use CMB2;

new Network_Options();

/**
 * Network options registration handler
 *
 * @package Amnesty\Multisite\Network\Options
 */
class Network_Options {

	/**
	 * The options object
	 *
	 * @var \CMB2|null
	 */
	protected ?CMB2 $options = null;

	/**
	 * Bind hooks
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', [ $this, 'register' ] );
	}

	/**
	 * Initialise the network options
	 *
	 * @return void
	 */
	public function register() {
		$this->options = new_cmb2_box(
			[
				'id'              => 'amnesty_network_options',
				/* translators: [admin] */
				'title'           => __( 'Network Options', 'amnesty' ),
				'object_types'    => [ 'options-page' ],
				'option_key'      => 'amnesty_network_options',
				'capability'      => is_multisite() ? 'manage_network_options' : 'manage_options',
				'admin_menu_hook' => is_multisite() ? 'network_admin_menu' : 'admin_menu',
				'tab_group'       => 'amnesty_network_options',
				'display_cb'      => 'amnesty_network_options_display_with_tabs',
			] 
		);

		// couldn't register CMB2 box
		if ( ! $this->options ) {
			return;
		}

		$this->register_features();
		$this->register_post_types();
		$this->register_taxonomies();
	}

	/**
	 * Register the theme feature options
	 *
	 * @return void
	 */
	protected function register_features(): void {
		$features = $this->options->add_field(
			[
				'id'         => 'enabled_features',
				/* translators: [admin] */
				'name'       => __( 'Features', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => __( 'Manage available features', 'amnesty' ),
				'type'       => 'group',
				'repeatable' => false,
			] 
		);

		$this->register_feature_languages( $features );
		$this->register_feature_popin( $features );
		$this->register_feature_filters( $features );
		$this->register_feature_post_single( $features );

		do_action( 'amnesty_register_features', $this->options, $features );
	}

	/**
	 * Register the feature-specific options for languages
	 *
	 * @param string $features the features group ID
	 *
	 * @return void
	 */
	protected function register_feature_languages( string $features ): void {
		$this->options->add_group_field(
			$features,
			[
				'id'         => 'language-selector',
				/* translators: [admin] */
				'name'       => __( 'Dedicated language selector', 'amnesty' ),
				'type'       => 'checkbox',
				'default_cb' => true,
			] 
		);

		$this->options->add_group_field(
			$features,
			[
				'id'      => 'language-button',
				/* translators: [admin] */
				'name'    => __( 'Language button menu', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Enables a button in the main navigation which triggers a language selection menu', 'amnesty' ),
				'type'    => 'checkbox',
				// theme companion is .org-specific
				'default' => false,
			] 
		);
	}

	/**
	 * Register the feature-specific options for pop-ins
	 *
	 * @param string $features the features group ID
	 *
	 * @return void
	 */
	protected function register_feature_popin( string $features ): void {
		$this->options->add_group_field(
			$features,
			[
				'id'      => 'pop-in',
				/* translators: [admin] */
				'name'    => __( 'Pop-in', 'amnesty' ),
				'type'    => 'checkbox',
				'default' => false,
			] 
		);
	}

	/**
	 * Register the feature-specific options for post filters
	 *
	 * @param string $features the features group ID
	 *
	 * @return void
	 */
	protected function register_feature_filters( string $features ): void {
		$this->options->add_group_field(
			$features,
			[
				'id'      => 'filter-type',
				/* translators: [admin] */
				'name'    => __( 'Index Page Filter Types', 'amnesty' ),
				'type'    => 'radio',
				'default' => 'categories',
				'options' => [
					/* translators: [admin] */
					'categories' => __( 'Categories Only', 'amnesty' ),
					/* translators: [admin] */
					'taxonomies' => __( 'Taxonomies & Terms', 'amnesty' ),
				],
			] 
		);
	}

	/**
	 * Register the feature-specific options for post single
	 *
	 * @param string $features the features group ID
	 *
	 * @return void
	 */
	protected function register_feature_post_single( string $features ): void {
		$this->options->add_group_field(
			$features,
			[
				'id'   => 'related-content-posts',
				/* translators: [admin] */
				'name' => __( 'Related Content (Posts)', 'amnesty' ),
				/* translators: [admin] */
				'desc' => __( 'Enable the Related Content area on the post single template', 'amnesty' ),
				'type' => 'checkbox',
			] 
		);
	}

	/**
	 * Register the theme post type options
	 *
	 * @return void
	 */
	protected function register_post_types(): void {
		$post_types = $this->options->add_field(
			[
				'id'         => 'enabled_post_types',
				/* translators: [admin] */
				'name'       => __( 'Post Types', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => __( 'Manage available post types', 'amnesty' ),
				'type'       => 'group',
				'repeatable' => false,
			] 
		);

		do_action( 'amnesty_register_post_type', $this->options, $post_types );
	}

	/**
	 * Register the theme taxonomy options
	 *
	 * @return void
	 */
	protected function register_taxonomies(): void {
		$taxonomies = $this->options->add_field(
			[
				'id'         => 'enabled_taxonomies',
				/* translators: [admin] */
				'name'       => __( 'Taxonomies', 'amnesty' ),
				/* translators: [admin] */
				'desc'       => __( 'Manage available taxonomies', 'amnesty' ),
				'type'       => 'group',
				'repeatable' => false,
			] 
		);

		do_action( 'amnesty_register_taxonomy', $this->options, $taxonomies );
	}

}
