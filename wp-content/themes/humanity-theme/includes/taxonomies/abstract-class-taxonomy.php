<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_REST_Response;
use WP_Taxonomy;
use WP_Term;

/**
 * Taxonomy base declaration
 *
 * @package Amnesty\Taxonomies
 */
abstract class Taxonomy {

	/**
	 * Option key for this taxonomy
	 *
	 * @var string
	 */
	protected $key = '';

	/**
	 * Taxonomy "name" - source language slug
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Taxonomy slug - target language slug
	 *
	 * @var string
	 */
	protected $slug = '';

	/**
	 * Object type(s) to register the taxonomy for
	 *
	 * @var array
	 */
	protected $object_types = [ 'post' ];

	/**
	 * Taxonomy registration args
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Whether the taxonomy is enabled by default
	 *
	 * @var bool
	 */
	protected $default_enabled = true;

	/**
	 * Sanitise and add hooks
	 */
	public function __construct() {
		$this->name = sanitize_title_with_dashes( $this->name );
		$this->key  = sprintf( 'amnesty_%s', $this->name );
		$this->slug = sanitize_title_with_dashes( get_option( "{$this->key}_slug" ) ?: $this->slug );

		add_action( 'amnesty_register_taxonomy', [ $this, 'declare' ], 10, 2 );

		if ( ! $this->is_enabled() ) {
			return;
		}

		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'template_include', [ $this, 'template' ] );
		add_filter( "rest_{$this->slug}_collection_params", [ $this, 'increase_limit' ] );
		add_filter( 'rest_prepare_taxonomy', [ $this, 'api_response' ], 10, 2 );
		add_action( 'amnesty_permalink_settings', [ $this, 'add_settings' ] );
		add_action( 'amnesty_permalink_settings_save', [ $this, 'save_settings' ] );

		// Term Add
		add_action( "{$this->slug}_pre_add_form", [ $this, 'form_open' ] );
		add_action( "{$this->slug}_add_form_fields", [ $this, 'add_form_close' ], 1 );

		// Yoast will handle it
		if ( defined( 'WPSEO_VERSION' ) ) {
			return;
		}

		// Term Edit
		add_action( "{$this->slug}_term_edit_form_top", [ $this, 'form_open' ] );
		add_action( "{$this->slug}_edit_form_fields", [ $this, 'edit_form_close' ], 1 );
	}

	/**
	 * Declare the taxonomy labels
	 *
	 * @param bool $defaults whether to return default labels or not
	 *
	 * @return object
	 */
	abstract public static function labels( bool $defaults = false ): object;

	/**
	 * Declare this taxonomy for feature support configuration
	 *
	 * @param mixed  $cmb2  the taxonomy options metabox
	 * @param string $group the taxonomy options group
	 *
	 * @return void
	 */
	public function declare( $cmb2, $group ): void {
		$cmb2->add_group_field(
			$group,
			[
				'id'      => $this->name,
				'name'    => $this::labels()->name,
				'type'    => 'checkbox',
				'default' => $this->default_enabled,
			]
		);
	}

	/**
	 * Register the taxonomy
	 *
	 * @return void
	 */
	public function register() {
		if ( ! $this->slug ) {
			return;
		}

		$objects = apply_filters( "amnesty_taxonomy_{$this->slug}_object_types", $this->object_types );

		register_taxonomy( $this->slug, $objects, $this->args() );

		$GLOBALS['wp']->add_query_var( "q{$this->name}" );
	}

	/**
	 * Load taxonomy template regardless of chosen slug
	 *
	 * @param string $template the current template choice
	 *
	 * @return string
	 */
	public function template( string $template = '' ) {
		if ( ! is_tax( $this->slug ) ) {
			return $template;
		}

		$new_template = sprintf( '%s/taxonomy-%s.php', get_stylesheet_directory(), $this->name );

		return file_exists( $new_template ) ? $new_template : $template;
	}

	/**
	 * Increase the maximum number of items per page in the API
	 *
	 * @param array $params existing available API parameters
	 *
	 * @return array
	 */
	public function increase_limit( array $params = [] ) {
		$params['per_page']['maximum'] = 250;

		return $params;
	}

	/**
	 * Register an "Amnesty" prop on Amnesty taxonomies in the API.
	 * This will allow us to filter taxonomies by those specific to
	 * the Amnesty WP Theme, which may be useful.
	 *
	 * @param WP_REST_Response $response the response object.
	 * @param WP_Taxonomy      $taxonomy the taxonomy object.
	 *
	 * @return WP_REST_Response
	 */
	public function api_response( WP_REST_Response $response, WP_Taxonomy $taxonomy ) {
		if ( $taxonomy->name !== $this->slug ) {
			return $response;
		}

		$data = $response->get_data();

		$data['amnesty'] = true;

		$response->set_data( $data );

		return $response;
	}

	/**
	 * Register taxonomy slug setting for localisation
	 *
	 * @return void
	 */
	public function add_settings() {
		require locate_template( 'partials/admin/permalinks-taxonomy.php' );
	}

	/**
	 * Save the localised taxonomy slug
	 *
	 * @param array $data $_POST data
	 *
	 * @return void
	 */
	public function save_settings( array $data = [] ) {
		if ( ! empty( $data[ "{$this->key}_slug" ] ) ) {
			$this->slug = sanitize_title_with_dashes( $data[ "{$this->key}_slug" ] );
			update_option( "{$this->key}_slug", $this->slug );
		}

		if ( ! empty( $data[ "{$this->key}_hierarchical" ] ) ) {
			update_option( "{$this->key}_hierarchical", $data[ "{$this->key}_hierarchical" ] );
		} else {
			update_option( "{$this->key}_hierarchical", 'off' );
		}

		if ( $this->slug !== $this->name ) {
			$this->update_db();
		}
	}

	/**
	 * Start output buffering and register callback to strip the description
	 * textarea that we'll be replacing with a TinyMCE instance
	 *
	 * @return void
	 */
	public function form_open() {
		ob_start(
			function ( $buffer ) {
				return preg_replace( '/<(tr|div) class="form-field term-description-wrap">(?!<\/\1>).*?<\/\1>/s', '', $buffer );
			}
		);
	}

	/**
	 * Flush output buffer (triggering callback in $this->form_open()),
	 * and add a TinyMCE instance for HTML description
	 *
	 * @return void
	 */
	public function add_form_close() {
		ob_end_flush();

		$settings = [
			'editor_class'  => 'i18n-multilingual',
			'media_buttons' => false,
			'quicktags'     => true,
			'textarea_name' => 'description',
			'textarea_rows' => 10,
		];

		?>
		<div class="form-field term-description-wrap">
			<label for="tag-description"><?php /* translators: [admin] */ esc_html_e( 'Description' ); ?></label>
			<?php wp_editor( '', 'description', $settings ); ?>
			<p><?php /* translators: [admin] */ esc_html_e( 'The description is not prominent by default; however, some themes may show it.' ); ?></p>
		</div>
		<script>jQuery(function($){var $doc=$(document);$('#addtag').on('mousedown','#submit',function(){tinyMCE.triggerSave();$doc.bind('ajaxSuccess.add_term',function(){tinyMCE.activeEditor.setContent('');$doc.unbind('ajaxSuccess.add_term',false)})})})</script>
		<?php
	}

	/**
	 * Flush output buffer (triggering callback in $this->form_open()),
	 * and add a TinyMCE instance for HTML description
	 *
	 * @param WP_Term $term current term
	 *
	 * @return void
	 */
	public function edit_form_close( WP_Term $term ) {
		ob_end_flush();

		$settings = [
			'editor_class'  => 'i18n-multilingual',
			'media_buttons' => false,
			'quicktags'     => true,
			'textarea_name' => 'description',
			'textarea_rows' => 10,
		];

		?>
		<tr class="form-field term-description-wrap">
			<th scope="row"><label for="description"><?php /* translators: [admin] */ esc_html_e( 'Description' ); ?></label></th>
			<td>
				<?php wp_editor( htmlspecialchars_decode( $term->description ), 'description', $settings ); ?>
				<p class="description"><?php /* translators: [admin] */ esc_html_e( 'The description is not prominent by default; however, some themes may show it.' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Parse and fill taxonomy registration args
	 *
	 * @return array
	 */
	protected function args() {
		$args = array_merge( $this->args, [ 'labels' => $this::labels( false ) ] );
		$args = wp_parse_args(
			$args,
			[
				'hierarchical' => false,
				'rewrite'      => true,
				'amnesty'      => true,
				'classname'    => static::class,
				'basename'     => $this->name,
			]
		);

		// no rewrite rules, no config required
		if ( false === $args['rewrite'] ) {
			return apply_filters( 'amnesty_taxonomy_registration_args', $args, $this->slug, $this->name );
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

		return apply_filters( 'amnesty_taxonomy_registration_args', $args, $this->slug, $this->name );
	}

	/**
	 * Default slug has changed - update the database accordingly
	 *
	 * @global $wpdb;
	 *
	 * @return void
	 */
	protected function update_db() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->update( $wpdb->term_taxonomy, [ 'taxonomy' => $this->slug ], [ 'taxonomy' => $this->name ] );
	}

	/**
	 * Whether the taxonomy is enabled globally
	 *
	 * @return boolean
	 */
	protected function is_enabled() {
		$options = get_site_option( 'amnesty_network_options' );

		if ( empty( $options['enabled_taxonomies'][0][ $this->slug ] ) ) {
			return $this->default_enabled;
		}

		return amnesty_validate_boolish( $options['enabled_taxonomies'][0][ $this->slug ] );
	}

}
