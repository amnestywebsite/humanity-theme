<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_custom_select_kses' ) ) {
	/**
	 * Add required tags & attributes to KSES for Custom Select block
	 *
	 * @param array<string,array<string,bool>> $tags currently-allowed HTML tags/attributes
	 *
	 * @return array<string,array<string,bool>>
	 */
	function amnesty_custom_select_kses( array $tags ): array {
		$attributes = _wp_add_global_attributes( [] );

		$tags = array_merge_recursive(
			$tags,
			[
				'form'  => array_merge(
					$attributes,
					[
						'method'    => true,
						'action'    => true,
						'data-info' => true,
					],
				),
				'label' => array_merge(
					$attributes,
					[
						'for'      => true,
						'tabindex' => true,
					],
				),
				'input' => array_merge(
					$attributes,
					[
						'type'         => true,
						'name'         => true,
						'value'        => true,
						'min'          => true,
						'max'          => true,
						'step'         => true,
						'placeholder'  => true,
						'disabled'     => true,
						'required'     => true,
						'checked'      => true,
						'autocomplete' => true,
					],
				),
			]
		);

		return $tags;
	}
}

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
				'is_control' => true,
				'is_form'    => false,
				'is_nav'     => false,
				'disabled'   => false,
				'multiple'   => false,
				'label'      => __( 'Choose an option', 'amnesty' ),
				'show_label' => false,
				'class'      => '',
				'name'       => amnesty_rand_str( 8 ),
				'active'     => '',
				'options'    => [],
			],
		);

		$block_args = [
			'type'       => 'control',
			'label'      => $params['label'],
			'showLabel'  => $params['show_label'],
			'className'  => $params['class'],
			'name'       => $params['name'],
			'multiple'   => $params['multiple'],
			'isDisabled' => $params['disabled'],
			'active'     => $params['active'],
			'options'    => [],
		];

		if ( true === $params['is_control'] ) {
			$block_args['type'] = 'control';
		}

		if ( true === $params['is_nav'] ) {
			$block_args['type'] = 'navigation';
		}

		if ( true === $params['is_form'] ) {
			$block_args['type'] = 'form';
		}

		if ( ! is_array( current( $params['options'] ) ) ) {
			foreach ( $params['options'] as $value => $label ) {
				$block_args['options'][] = compact( 'value', 'label' );
			}
		}

		if ( is_array( $block_args['active'] ) && ! $block_args['multiple'] ) {
			$block_args['active'] = array_shift( $block_args['active'] );
		}

		add_filter( 'wp_kses_allowed_html', 'amnesty_custom_select_kses' );
		echo wp_kses_post(
			do_blocks(
				sprintf(
					'<!-- wp:amnesty-core/custom-select %s /-->',
					wp_kses_data( wp_json_encode( $block_args ) ),
				),
			),
		);
		remove_filter( 'wp_kses_allowed_html', 'amnesty_custom_select_kses' );
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
