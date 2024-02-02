<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_Term;

new Taxonomy_Locations();

/**
 * Register the Locations taxonomy
 *
 * @package Amnesty\Taxonomies
 */
class Taxonomy_Locations extends Taxonomy {

	/**
	 * Taxonomy slug
	 *
	 * @var string
	 */
	protected $name = 'location';

	/**
	 * Taxonomy slug
	 *
	 * @var string
	 */
	protected $slug = 'location';

	/**
	 * Object type(s) to register the taxonomy for
	 *
	 * @var array
	 */
	protected $object_types = [ 'attachment', 'page', 'post' ];

	/**
	 * Taxonomy registration arguments
	 *
	 * @var array
	 */
	protected $args = [
		'hierarchical'          => true,
		'rewrite'               => false,
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'query_var'             => false,
		'update_count_callback' => '_update_generic_term_count',
	];

	/**
	 * Whether the taxonomy is enabled by default
	 *
	 * @var bool
	 */
	protected $default_enabled = false;

	/**
	 * Add hooks
	 */
	public function __construct() {
		parent::__construct();

		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'admin_head', [ $this, 'output_css' ] );
		add_filter( 'term_link', [ $this, 'rewrite_links' ], 10, 3 );
	}

	/**
	 * Declare the taxonomy labels
	 *
	 * @param bool $defaults whether to return default labels or not
	 *
	 * @return object
	 */
	public static function labels( bool $defaults = false ): object {
		$default_labels = [
			/* translators: [admin] */
			'name'                  => _x( 'Countries', 'taxonomy general name', 'amnesty' ),
			/* translators: [admin] */
			'singular_name'         => _x( 'Country', 'taxonomy singular name', 'amnesty' ),
			/* translators: [admin] */
			'search_items'          => __( 'Search Countries', 'amnesty' ),
			/* translators: [admin] */
			'all_items'             => __( 'All Countries', 'amnesty' ),
			/* translators: [admin] */
			'parent_item'           => __( 'Parent Country', 'amnesty' ),
			/* translators: [admin] */
			'parent_item_colon'     => __( 'Parent Country:', 'amnesty' ),
			/* translators: [admin] */
			'edit_item'             => __( 'Edit Country', 'amnesty' ),
			/* translators: [admin] */
			'view_item'             => __( 'View Country', 'amnesty' ),
			/* translators: [admin] */
			'update_item'           => __( 'Update Country', 'amnesty' ),
			/* translators: [admin] */
			'add_new_item'          => __( 'Add New Country', 'amnesty' ),
			/* translators: [admin] */
			'new_item_name'         => __( 'New Country Name', 'amnesty' ),
			/* translators: [admin] */
			'add_or_remove_items'   => __( 'Add or remove Countries', 'amnesty' ),
			/* translators: [admin] */
			'choose_from_most_used' => __( 'Choose from most frequently used Countries', 'amnesty' ),
			/* translators: [admin] */
			'not_found'             => __( 'No Countries found.', 'amnesty' ),
			/* translators: [admin] */
			'no_terms'              => __( 'No Countries', 'amnesty' ),
			/* translators: [admin] */
			'items_list_navigation' => __( 'Countries list navigation', 'amnesty' ),
			/* translators: [admin] */
			'items_list'            => __( 'Countries list', 'amnesty' ),
			/* translators: [admin] Tab heading when selecting from the most used terms. */
			'most_used'             => _x( 'Most Used', 'countries', 'amnesty' ),
			/* translators: [admin] */
			'back_to_items'         => __( '&larr; Back to Countries', 'amnesty' ),
		];

		if ( $defaults ) {
			return (object) $default_labels;
		}

		$options = get_option( 'amnesty_localisation_options_page' );

		if ( ! isset( $options['location_labels'][0] ) ) {
			return (object) $default_labels;
		}

		$config_labels = [];

		foreach ( $options['location_labels'][0] as $key => $value ) {
			$key = str_replace( 'location_label_', '', $key );

			$config_labels[ $key ] = $value;
		}

		return (object) array_merge( $default_labels, $config_labels );
	}

	/**
	 * Load taxonomy template regardless of chosen slug
	 *
	 * @param string $template the current template choice
	 *
	 * @return string
	 */
	public function template( string $template = '' ) {
		// no-op
		return $template;
	}

	/**
	 * Add term "type" to body class
	 *
	 * @param array $classes existing classes
	 *
	 * @return array
	 */
	public function body_classes( array $classes = [] ) {
		if ( ! is_tax( $this->slug ) ) {
			return $classes;
		}

		$classes[] = get_term_meta( get_queried_object_id(), 'type', true ) ?: 'default';

		return $classes;
	}

	/**
	 * Adjust CMB2 layout
	 *
	 * @return void
	 */
	public function output_css() {
		echo '<style>.edit-tags-php .cmb-repeatable-group .cmb2-upload-button{float:none}.term-php .cmb-row:not(.cmb-repeatable-grouping){padding:1em 0}</style>';
	}

	/**
	 * Filter links for terms in this taxonomy
	 *
	 * Rewrites them to filtered search URIs
	 *
	 * @param string  $link     the generated link
	 * @param WP_Term $term     the term object
	 * @param string  $taxonomy the taxonomy slug
	 *
	 * @return string
	 */
	public function rewrite_links( string $link, WP_Term $term, string $taxonomy ): string {
		if ( $taxonomy !== $this->slug ) {
			return $link;
		}

		if ( ! apply_filters( 'amnesty_taxonomy_send_to_search', true, $taxonomy ) ) {
			return $link;
		}

		return esc_url( sprintf( '%s?q%s=%s', amnesty_search_url(), $this->slug, $term->term_id ) );
	}

}
