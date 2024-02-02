<?php

declare( strict_types = 1 );

if ( ! function_exists( 'cmb2_validate_integer' ) ) {
	/**
	 * Validate and sanitize a value as integer
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param mixed $value the value to validate and sanitise
	 *
	 * @return integer
	 */
	function cmb2_validate_integer( $value ) {
		return is_numeric( $value ) ? absint( $value ) : 0;
	}
}

if ( ! function_exists( 'amnesty_options_display_with_tabs' ) ) {
	/**
	 * A CMB2 options-page display callback override which adds tab navigation among
	 * CMB2 options pages which share this same display callback.
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param CMB2_Options_Hookup $cmb_options - The CMB2_Options_Hookup object.
	 *
	 * @return void
	 */
	function amnesty_options_display_with_tabs( $cmb_options ) {
		// v nonce check not required here
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = sanitize_text_field( $_GET['page'] ?? '' );
		$tabs = amnesty_options_page_tabs( $cmb_options );

		?>
		<div class="wrap cmb2-options-page option-<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php if ( get_admin_page_title() ) : ?>
				<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
			<?php endif; ?>
			<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
				<a class="nav-tab <?php $option_key === $page && print 'nav-tab-active'; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
			</h2>
			<form id="<?php echo esc_attr( $cmb_options->cmb->cmb_id ); ?>" class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data" encoding="multipart/form-data">
				<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
				<?php $cmb_options->options_page_metabox(); ?>
				<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'amnesty_network_options_display_with_tabs' ) ) {
	/**
	 * A CMB2 options-page display callback override which adds tab navigation among
	 * CMB2 options pages which share this same display callback.
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param CMB2_Options_Hookup $cmb_options - The CMB2_Options_Hookup object.
	 *
	 * @return void
	 */
	function amnesty_network_options_display_with_tabs( $cmb_options ) {
		// v nonce check not required here
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = sanitize_text_field( $_GET['page'] ?? '' );
		$tabs = amnesty_options_page_tabs( $cmb_options );

		?>
		<div class="wrap cmb2-options-page option-<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php if ( get_admin_page_title() ) : ?>
				<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
			<?php endif; ?>
			<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
				<a class="nav-tab <?php $option_key === $page && print 'nav-tab-active'; ?>" href="<?php network_menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
			</h2>
			<form id="<?php echo esc_attr( $cmb_options->cmb->cmb_id ); ?>" class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data" encoding="multipart/form-data">
				<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
				<?php $cmb_options->options_page_metabox(); ?>
				<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'amnesty_options_page_tabs' ) ) {
	/**
	 * Gets navigation tabs array for CMB2 options pages which share the given
	 * display_cb param.
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 *
	 * @return array Array of tab information.
	 */
	function amnesty_options_page_tabs( $cmb_options ) {
		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = [];

		foreach ( CMB2_Boxes::get_all() as $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$key = $cmb->options_page_keys()[0];
				$val = $cmb->prop( 'tab_title' ) ? $cmb->prop( 'tab_title' ) : $cmb->prop( 'title' );

				$tabs[ $key ] = $val;
			}
		}

		return $tabs;
	}
}
