<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_REST_Response;
use WP_Taxonomy;
use WP_Term;

new Taxonomy_Content_Types();

/**
 * Register the Content Types taxonomy
 *
 * @package Amnesty\Taxonomies
 */
class Taxonomy_Content_Types {

	/**
	 * Original taxonomy slug
	 *
	 * @var string
	 */
	protected $slug = 'category';

	/**
	 * Add hooks
	 */
	public function __construct() {
		add_action( 'registered_taxonomy', [ $this, 'modify_taxonomy' ] );
		add_filter( "taxonomy_labels_{$this->slug}", [ static::class, 'labels' ], 10, 0 );
		add_filter( 'rest_prepare_taxonomy', [ $this, 'api_response' ], 10, 2 );
		add_filter( 'template_include', [ $this, 'template' ] );

		add_filter( 'query_vars', fn ( array $vars ): array => array_merge( $vars, [ "q{$this->slug}" ] ) );

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
	 * Add Amnesty prop to Category taxonomy
	 *
	 * @param string $taxonomy the taxonomy slug
	 *
	 * @return void
	 */
	public function modify_taxonomy( string $taxonomy ) {
		if ( $taxonomy !== $this->slug ) {
			return;
		}

		/**
		 * Global taxonomy store
		 *
		 * @var array<string,\WP_Taxonomy> $wp_taxonomies
		 */
		global $wp_taxonomies;
		$wp_taxonomies[ $taxonomy ]->rest_base = $this->slug;
		$wp_taxonomies[ $taxonomy ]->amnesty   = true;
		$wp_taxonomies[ $taxonomy ]->classname = static::class;
		$wp_taxonomies[ $taxonomy ]->basename  = 'category';

		// if the SharePoint plugin is enabled, include attachments in the taxonomy counts
		if ( defined( 'SP_DIR' ) ) {
			$wp_taxonomies[ $taxonomy ]->update_count_callback = '_update_generic_term_count';
		}
	}

	/**
	 * Rename "Category" to "Content Type"
	 *
	 * @param bool $defaults whether to return default values or not
	 *
	 * @return object
	 */
	public static function labels( bool $defaults = false ): object {
		$default_labels = [
			/* translators: [admin] */
			'menu_name'                  => _x( 'Content Types', 'taxonomy general name', 'amnesty' ),
			/* translators: [admin] */
			'name'                       => _x( 'Content Types', 'taxonomy general name', 'amnesty' ),
			/* translators: [admin] */
			'singular_name'              => _x( 'Content Type', 'taxonomy singular name', 'amnesty' ),
			/* translators: [admin] */
			'search_items'               => __( 'Search Content Types', 'amnesty' ),
			'popular_items'              => null,
			/* translators: [admin] */
			'all_items'                  => __( 'All Content Types', 'amnesty' ),
			/* translators: [admin] */
			'parent_item'                => __( 'Parent Content Type', 'amnesty' ),
			/* translators: [admin] */
			'parent_item_colon'          => __( 'Parent Content Type:', 'amnesty' ),
			/* translators: [admin] */
			'edit_item'                  => __( 'Edit Content Type', 'amnesty' ),
			/* translators: [admin] */
			'view_item'                  => __( 'View Content Type', 'amnesty' ),
			/* translators: [admin] */
			'update_item'                => __( 'Update Content Type', 'amnesty' ),
			/* translators: [admin] */
			'add_new_item'               => __( 'Add New Content Type', 'amnesty' ),
			/* translators: [admin] */
			'new_item_name'              => __( 'New Content Type', 'amnesty' ),
			'separate_items_with_commas' => null,
			'add_or_remove_items'        => null,
			'choose_from_most_used'      => null,
			/* translators: [admin] */
			'not_found'                  => __( 'No Content Types found.', 'amnesty' ),
			/* translators: [admin] */
			'no_terms'                   => __( 'No Content Types', 'amnesty' ),
			/* translators: [admin] */
			'items_list_navigation'      => __( 'Content Types list navigation', 'amnesty' ),
			/* translators: [admin] */
			'items_list'                 => __( 'Content Types list', 'amnesty' ),
			/* translators: [admin] Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'Content Types', 'amnesty' ),
			/* translators: [admin] */
			'back_to_items'              => __( '&larr; Back to Content Types', 'amnesty' ),
		];

		if ( $defaults ) {
			return (object) $default_labels;
		}

		$options = get_option( 'amnesty_localisation_options_page' );

		if ( ! isset( $options['category_labels'][0] ) ) {
			return (object) $default_labels;
		}

		$config_labels = [];

		foreach ( $options['category_labels'][0] as $key => $value ) {
			$key = str_replace( 'category_label_', '', $key );

			$config_labels[ $key ] = $value;
		}

		return (object) array_merge( $default_labels, $config_labels );
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
	 * Replace description textarea with TinyMCE instance
	 *
	 * @param \WP_Term $term the current term
	 *
	 * @return void
	 */
	public function fields( WP_Term $term ) {
		printf( '<tr valign="top"><th scope="row">%s</th>', /* translators: [admin] */ esc_html__( 'Description', 'amnesty' ) );
		echo '<td>';
		wp_editor( html_entity_decode( $term->description ), 'description', [ 'media_buttons' => false ] );
		echo '</td></tr>';
		echo '<script>jQuery(function($){$("label[for=description]").parent().parent().remove()});</script>';
	}

	/**
	 * Load taxonomy template regardless of chosen slug
	 *
	 * @param string $template the currently-chosen template
	 *
	 * @return string
	 */
	public function template( string $template = '' ) {
		if ( is_tax( $this->slug ) ) {
			return sprintf( '%s/taxonomy-%s.php', get_stylesheet_directory(), str_replace( '_', '-', $this->slug ) );
		}
		return $template;
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

}
