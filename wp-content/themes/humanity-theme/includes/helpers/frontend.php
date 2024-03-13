<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_custom_select' ) ) {
	/**
	 * Render an accessible custom select-type element
	 *
	 * @package Amnesty
	 *
	 * @param array $params {
	 *     @type string $name    the identifier for the select
	 *     @type string $label   the label shown for the select
	 *     @type bool   $is_form whether to output a form or a div
	 *     @type string $type    type of form. used when $is_form = true. accepts 'nav', 'filter'
	 *     @type string $active  the pre-selected option's value
	 *     @type array  $options the list of options in value=>label pairs
	 * }
	 *
	 * @return void
	 */
	function amnesty_render_custom_select( array $params = [] ): void {
		$params = wp_parse_args(
			$params,
			[
				/* translators: [front] AM not sure yet */
				'label'      => __( 'Choose an option', 'amnesty' ),
				'show_label' => false,
				'name'       => amnesty_rand_str( 8 ),
				'is_form'    => false,
				'is_control' => false,
				'is_nav'     => false,
				'multiple'   => false,
				'disabled'   => false,
				'active'     => '',
				'class'      => '',
				'options'    => [
					/* translators: [front] AM not sure yet */
					'label'   => __( 'Choose an option', 'amnesty' ),
					'is_form' => false,
					'active'  => '',
					'class'   => '',
					'options' => [
						/* translators: [front] AM not sure yet */
						'' => __( 'Choose an option', 'amnesty' ),
					],
				],
			]
		);

		$is_form = amnesty_validate_boolish( $params['is_form'] );

		if ( ! $is_form ) {
			unset( $params['type'] );
		}

		if ( ! empty( $params['type'] ) && ! in_array( $params['type'], [ 'nav', 'filter' ], true ) ) {
			$params['type'] = 'filter';
		}

		if ( true === $params['multiple'] ) {
			require locate_template( 'partials/forms/multiselect.php' );
			return;
		}

		if ( true === $params['is_form'] ) {
			require locate_template( 'partials/forms/select-form.php' );
			return;
		}

		if ( true === $params['is_nav'] ) {
			require locate_template( 'partials/forms/select-nav.php' );
			return;
		}

		require locate_template( 'partials/forms/select-control.php' );
	}
}

if ( ! function_exists( 'amnesty_get_query_var' ) ) {
	/**
	 * Retrieve a query variable
	 *
	 * Prefers WP_Query query vars, falls back to WP query vars
	 *
	 * @package Amnesty
	 *
	 * @param string $variable the variable to retrieve
	 *
	 * @return mixed
	 */
	function amnesty_get_query_var( string $variable ) {
		/** Global WP object @var \WP $wp */
		global $wp;

		return get_query_var( $variable ) ?: ( $wp->query_vars[ $variable ] ?? '' );
	}
}

if ( ! function_exists( 'amnesty_render_blocks' ) ) {
	/**
	 * Render an array of blocks
	 *
	 * @package Amnesty
	 *
	 * @param array<int,array<string,mixed>> $blocks the blocks to render
	 *
	 * @return string
	 */
	function amnesty_render_blocks( array $blocks ): string {
		return implode( '', array_map( 'render_block', $blocks ) );
	}
}
