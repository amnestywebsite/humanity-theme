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

		add_action( 'cmb2_admin_init', [ $this, 'custom_fields' ] );
		add_action( 'rest_api_init', [ $this, 'register_fields' ] );
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

	/**
	 * Register custom fields for this taxonomy
	 *
	 * @return void
	 */
	public function custom_fields() {
		if ( ! function_exists( 'new_cmb2_box' ) ) {
			return;
		}

		$settings = new_cmb2_box(
			[
				'id'           => sprintf( 'amnesty_%s', $this->name ),
				/* translators: [admin] */
				'title'        => __( 'Term settings', 'amnesty' ),
				'object_types' => [ 'term' ],
				'taxonomies'   => [ $this->name ],
				'context'      => 'normal',
				'priority'     => 'low',
				'show_on_cb'   => function () {
					// v nonce check not required
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return is_admin() && sanitize_text_field( $_GET['taxonomy'] ?? '' ) === $this->name;
				},
			]
		);

		amnesty_cmb2_wrap_open( $settings, /* translators: [admin] */ __( 'Basic Settings', 'amnesty' ) );

		$settings->add_field(
			[
				'id'           => 'image',
				/* translators: [admin] */
				'name'         => __( 'Featured Image', 'amnesty' ),
				'type'         => 'file',
				'options'      => [ 'url' => false ],
				'text'         => [ 'add_upload_file_text' => /* translators: [admin] */ __( 'Choose or upload an image', 'amnesty' ) ],
				'preview_size' => 'thumbnail',
				'query_args'   => [
					'type' => [
						'image/jpeg',
						'image/png',
						'image/gif',
						'image/bmp',
						'image/tiff',
					],
				],
			]
		);

		do_action( 'amnesty_taxonomy_location_custom_fields', $settings, 'basic' );

		amnesty_cmb2_wrap_close( $settings );

		do_action( "taxonomy_{$this->name}_custom_fields", $settings );
	}

	/**
	 * Register REST API fields for this taxonomy
	 *
	 * @return void
	 */
	public function register_fields() {
		register_rest_field(
			$this->name,
			'featuredImage',
			[
				'get_callback'    => [ static::class, 'featured_image_get_callback' ],
				'update_callback' => [ static::class, 'featured_image_set_callback' ],
			]
		);
	}

	/**
	 * Getter for the featuredImage rest field
	 *
	 * @param array<string,mixed> $term the term to retrieve the featured image for
	 *
	 * @return array|null
	 */
	public static function featured_image_get_callback( array $term ): ?array {
		$image_id = absint( get_term_meta( $term['id'], 'image_id', true ) );

		if ( ! $image_id ) {
			return null;
		}

		$image = wp_prepare_attachment_for_js( $image_id );

		return $image ?: null;
	}

	/**
	 * Setter for the featuredImage rest field
	 *
	 * @param int     $value the value to set
	 * @param WP_Term $term  the term being updated
	 *
	 * @return bool
	 */
	public static function featured_image_set_callback( int $value, WP_Term $term ): bool {
		$image = wp_get_attachment_image_url( absint( $value ) );

		if ( false === $image ) {
			return false;
		}

		$success = update_term_meta( $term->term_id, 'image_id', absint( $value ) );

		return ! is_int( $success ) || true === $success;
	}

}
