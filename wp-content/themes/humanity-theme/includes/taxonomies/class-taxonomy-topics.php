<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_Term;

new Taxonomy_Topics();

/**
 * Register the Topics taxonomy
 *
 * @package Amnesty\Taxonomies
 */
class Taxonomy_Topics extends Taxonomy {

	/**
	 * Taxonomy slug
	 *
	 * @var string
	 */
	protected $name = 'topic';

	/**
	 * Taxonomy slug
	 *
	 * @var string
	 */
	protected $slug = 'topic';

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
	protected $default_enabled = true;

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		parent::__construct();

		if ( ! $this->is_enabled() ) {
			return;
		}

		add_filter( 'term_link', [ $this, 'rewrite_links' ], 10, 3 );

		// has to be run late to ensure the taxonomy is registered
		add_action( 'init', [ $this, 'redirect' ] );
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

		return esc_url( sprintf( '%s?q%s=%s', amnesty_search_url(), $this->slug, $term->term_id ) );
	}

	/**
	 * Redirect old term URIs to search
	 *
	 * @return void
	 */
	public function redirect(): void {
		// just in case
		if ( headers_sent() || is_admin() ) {
			return;
		}

		$path  = wp_parse_url( current_url(), PHP_URL_PATH ) ?: '/';
		$regex = sprintf( '#^/[a-z]{2}/%s/([a-z-]+)/$#', preg_quote( $this->slug, '#' ) );

		// not an old topic url
		if ( 1 !== preg_match( $regex, $path, $match ) || ! isset( $match[1] ) ) {
			return;
		}

		$term = get_term_by( 'slug', $match[1], $this->slug );

		// can't find the term
		if ( ! is_a( $term, WP_Term::class ) ) {
			return;
		}

		// redirect to filtered search
		wp_safe_redirect( add_query_arg( [ "q{$this->slug}" => $term->term_id ], amnesty_search_url() ) );
		die;
	}

	/**
	 * Register taxonomy slug setting for localisation
	 *
	 * @return void
	 */
	public function add_settings() {
		// no-op
	}

	/**
	 * Save the localised taxonomy slug
	 *
	 * @param array $data $_POST data
	 *
	 * @return void
	 */
	public function save_settings( array $data = [] ) {
		// no-op
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
			'name'                  => _x( 'Topics', 'taxonomy general name', 'amnesty' ),
			/* translators: [admin] */
			'singular_name'         => _x( 'Topic', 'taxonomy singular name', 'amnesty' ),
			/* translators: [admin] */
			'search_items'          => __( 'Search Topics', 'amnesty' ),
			/* translators: [admin] */
			'all_items'             => __( 'All Topics', 'amnesty' ),
			/* translators: [admin] */
			'parent_item'           => __( 'Parent Topic', 'amnesty' ),
			/* translators: [admin] */
			'parent_item_colon'     => __( 'Parent Topic:', 'amnesty' ),
			/* translators: [admin] */
			'edit_item'             => __( 'Edit Topic', 'amnesty' ),
			/* translators: [admin] */
			'view_item'             => __( 'View Topic', 'amnesty' ),
			/* translators: [admin] */
			'update_item'           => __( 'Update Topic', 'amnesty' ),
			/* translators: [admin] */
			'add_new_item'          => __( 'Add New Topic', 'amnesty' ),
			/* translators: [admin] */
			'new_item_name'         => __( 'New Topic', 'amnesty' ),
			/* translators: [admin] */
			'add_or_remove_items'   => __( 'Add or remove Topics', 'amnesty' ),
			/* translators: [admin] */
			'choose_from_most_used' => __( 'Choose from most frequently used Topics', 'amnesty' ),
			/* translators: [admin] */
			'not_found'             => __( 'No Topics found.', 'amnesty' ),
			/* translators: [admin] */
			'no_terms'              => __( 'No Topics', 'amnesty' ),
			/* translators: [admin] */
			'items_list_navigation' => __( 'Topics list navigation', 'amnesty' ),
			/* translators: [admin] */
			'items_list'            => __( 'Topics list', 'amnesty' ),
			/* translators: [admin] Tab heading when selecting from the most used terms. */
			'most_used'             => _x( 'Most Used', 'topics', 'amnesty' ),
			/* translators: [admin] */
			'back_to_items'         => __( '&larr; Back to Topics', 'amnesty' ),
		];

		if ( $defaults ) {
			return (object) $default_labels;
		}

		$options = get_option( 'amnesty_localisation_options_page' );

		if ( ! isset( $options['topic_labels'][0] ) ) {
			return (object) $default_labels;
		}

		$config_labels = [];

		foreach ( $options['topic_labels'][0] as $key => $value ) {
			$key = str_replace( 'topic_label_', '', $key );

			$config_labels[ $key ] = $value;
		}

		return (object) array_merge( $default_labels, $config_labels );
	}

}
