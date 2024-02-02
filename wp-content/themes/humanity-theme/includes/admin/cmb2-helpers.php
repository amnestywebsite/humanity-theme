<?php

declare( strict_types = 1 );

if ( ! has_action( 'cmb2_render_noop', '__return_null' ) ) {
	add_action( 'cmb2_render_noop', '__return_null' );
}

if ( ! has_filter( 'cmb2_sanitize_noop', '__return_null' ) ) {
	add_filter( 'cmb2_sanitize_noop', '__return_null' );
}

if ( ! function_exists( 'amnesty_cmb2_wrap_open_row_callback' ) ) {
	/**
	 * Render callback for opening a cmb2 wrapper
	 *
	 * @see amnesty_cmb2_wrap_open
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param string $title   the wrapper's title
	 * @param bool   $closed  whether wrapper is collapsed
	 * @param bool   $hidden  whether wrapper is hidden
	 * @param array  $data    any data attributes to add
	 *
	 * @return void
	 */
	function amnesty_cmb2_wrap_open_row_callback( string $title = '', bool $closed = false, bool $hidden = false, array $data = [] ): void {
		echo '<div';
		printf(
			' class="postbox cmb-type-group cmb-repeatable-grouping cmb-repeatable-group %s %s" ',
			esc_attr( $closed ? 'closed' : '' ),
			esc_attr( $hidden ? 'is-hidden' : '' )
		);

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				printf( 'data-%s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}
		}
		echo '>';

		echo '<div class="cmbhandle" title="Click to toggle"><br></div>';
		printf( '<h3 class="cmb-group-title cmbhandle-title"><span>%s</span></h3>', esc_html( $title ) );
		echo '<div class="inside cmb-td cmb-nested cmb-field-list">';
	}
}

if ( ! function_exists( 'amnesty_cmb2_wrap_open' ) ) {
	/**
	 * Output a fake field to open a group wrapper
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param mixed  $metabox the metabox object
	 * @param string $title   the wrapper's title
	 * @param bool   $closed  whether wrapper is collapsed
	 * @param bool   $hidden  whether wrapper is hidden
	 * @param array  $data    any data attributes to add
	 *
	 * @return void
	 */
	function amnesty_cmb2_wrap_open( $metabox, string $title = '', bool $closed = false, bool $hidden = false, array $data = [] ) {
		$metabox->add_field(
			[
				'id'            => random_bytes( 4 ),
				'type'          => 'noop',
				'render_row_cb' => fn () => amnesty_cmb2_wrap_open_row_callback( $title, $closed, $hidden, $data ),
			]
		);
	}
}

if ( ! function_exists( 'amnesty_cmb2_wrap_close' ) ) {
	/**
	 * Output a fake field to close a group wrapper
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param mixed $metabox the metabox object
	 *
	 * @return void
	 */
	function amnesty_cmb2_wrap_close( $metabox ) {
		$metabox->add_field(
			[
				'id'            => random_bytes( 4 ),
				'type'          => 'noop',
				'render_row_cb' => function () {
					echo '</div></div>';
				},
			]
		);
	}
}

// fix issue with cmb2 checkbox default value being set to true and then never saving
// https://github.com/CMB2/CMB2/issues/809

add_filter( 'cmb2_sanitize_toggle', 'amnesty_sanitize_checkbox', 20, 2 );

if ( ! function_exists( 'amnesty_sanitize_checkbox' ) ) {
	/**
	 * Fixed checkbox issue with default is true.
	 *
	 * @package Amnesty\Plugins\CMB2
	 *
	 * @param  mixed $override_value Sanitization/Validation override value to return.
	 * @param  mixed $value          The value to be saved to this field.
	 * @return mixed
	 */
	function amnesty_sanitize_checkbox( $override_value, $value ) {
		// Return 0 instead of false if null value given. This hack for
		// checkbox or checkbox-like can be setting true as default value.
		return is_null( $value ) ? 0 : $value;
	}
}
