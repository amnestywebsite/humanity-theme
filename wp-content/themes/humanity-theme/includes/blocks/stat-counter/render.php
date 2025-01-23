<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_stat_counter_block' ) ) {
	/**
	 * Render Stat Counter block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes Block attributes
	 *
	 * @return string
	 */
	function render_stat_counter_block( array $attributes ): string {
		$options = get_option( 'amnesty_localisation_options_page' );

		$attributes = wp_parse_args(
			$attributes,
			[
				'alignment' => '',
				'duration'  => 2,
				'value'     => '',
			]
		);

		$alignment = 'align' . $attributes['alignment'];
		$duration  = $attributes['duration'];
		$value     = $attributes['value'];

		if ( 'on' === ( $options['enforce_grouping_separators'] ?? false ) ) {
			$value = number_format_i18n( $value );
		}

		$wrapper_attributes = get_block_wrapper_attributes(
			[
				'class' => $alignment,
			]
		);

		return sprintf(
			'<div %1$s data-duration="%2$s" data-value="%3$s">%4$s</div>',
			wp_kses_data( $wrapper_attributes ),
			esc_attr( $duration ),
			esc_attr( $value ),
			wp_kses_post( $value )
		);
	}
}
