<?php

declare( strict_types = 1 );

namespace Amnesty;

new Permalink_Settings();

/**
 * Register permalink settings field groups
 *
 * @package Amnesty\Admin\Options
 */
class Permalink_Settings {

	/**
	 * Add actions
	 */
	public function __construct() {
		add_action(
			'admin_init',
			function () {
				$this->register();
				$this->save();
			} 
		);
	}

	/**
	 * Register a new settings section in the permalinks settings page
	 *
	 * @return void
	 */
	protected function register() {
		add_settings_section( 'amnesty-permalink', /* translators: [admin] */ __( 'Amnesty Permalinks', 'amnesty' ), [ $this, 'render' ], 'permalink' );
	}

	/**
	 * Render the settings section
	 *
	 * @return void
	 */
	public function render() {
		echo wp_kses_post( wpautop( /* translators: [admin] */ __( 'If you like, you may enter custom URL slugs for post types and taxonomies here. This allows you to create URL structures in your language.', 'amnesty' ) ) );
		echo wp_kses_post( wpautop( /* translators: [admin] */ __( 'Please note that changing these from their defaults may require you to set rewrite rules if you have existing content. Only change them if you know what you are doing.', 'amnesty' ) ) );
		wp_nonce_field( 'amnesty-permalinks', 'amnesty-permalinks', false );
		echo '<input type="hidden" name="amnesty_permalink_settings" value="1">';
		echo '<table class="form-table" role="presentation"><tbody>';
		do_action( 'amnesty_permalink_settings' );
		echo '</tbody></table>';
	}

	/**
	 * Save the fields in the settings
	 *
	 * @return void
	 */
	protected function save() {
		// invalid page
		if ( ! wp_get_referer() || false === strpos( wp_get_referer(), 'options-permalink.php' ) ) {
			return;
		}

		// invalid data or not a POST request
		if ( ! isset( $_POST['amnesty_permalink_settings'] ) ) {
			return;
		}

		// verify submission
		if ( false === check_admin_referer( 'amnesty-permalinks', 'amnesty-permalinks' ) ) {
			return;
		}

		// we're good; allow settings save
		do_action( 'amnesty_permalink_settings_save', $_POST );
	}

}
