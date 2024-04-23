<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_links_with_icons_block' ) ) {
	/**
	 * Render a links with icons block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array  $attrs the block attributes
	 * @param string $content the block content
	 *
	 * @return string
	 */
	function render_links_with_icons_block( array $attrs = [], string $content = '' ): string {
		$attrs = wp_parse_args(
			$attrs,
			[
				'quantity'        => 0,
				'orientation'     => 'horizontal',
				'backgroundColor' => '',
				'hideLines'       => false,
				'dividerIcon'     => 'none',
				'className'       => '',
			]
		);

		$quantity         = $attrs['quantity'];
		$orientation      = $attrs['orientation'];
		$background_color = $attrs['backgroundColor'];
		$hide_lines       = $attrs['hideLines'];
		$divider_icon     = $attrs['dividerIcon'];
		$is_style_square  = $attrs['className'];


		$classes = classnames(
			'linksWithIcons-group',
			[
				'is-' . $orientation,
				'has-' . $quantity . '-items',
				'has-background'        => (bool) $background_color,
				'has-no-lines'          => (bool) $hide_lines,
				'has-' . $background_color . '-background-color' => (bool) $background_color,
				'icon-' . $divider_icon => (bool) $divider_icon,
				$is_style_square,
			]
		);

		return sprintf(
			'<div class="%1$s">
				%2$s
			</div>',
			esc_attr( $classes ),
			wp_kses_post( $content )
		);
	}
}
