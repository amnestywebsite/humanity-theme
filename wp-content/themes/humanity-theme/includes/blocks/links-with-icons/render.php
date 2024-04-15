<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_links_with_icons_block' ) ) {
	/**
	 * Render a links with icons block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attrs the block attributes
	 *
	 * @return string|null
	 */
	function render_links_with_icons_block( $attrs = [], $content ) {
		$attrs = apply_filters( 'links_with_icons_block_attributes', $attrs );

		$quantity = $attrs['quantity'] ?? 0;
		$orientation = $attrs['orientation'] ?? 'horizontal';
		$background_color = $attrs['backgroundColor'] ?? '';
		$hide_lines = $attrs['hideLines'] ?? false;
		$divider_icon = $attrs['dividerIcon'] ?? 'none';
		$is_style_square = $attrs['className'] ?? '';

		$classes = classnames(
				'linksWithIcons-group',
				[
					'is-' . $orientation,
					'has-' . $quantity . '-items',
					'has-background' => !! $background_color,
					'has-no-lines' => !! $hide_lines,
					'has-' . $background_color . '-background-color' => !! $background_color,
					'icon-' . $divider_icon => !! $divider_icon,
					$is_style_square
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
