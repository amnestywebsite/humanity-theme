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
		if ( false !== strpos( $content, 'linksWithIcons-group' ) ) {
			return $content;
		}

		$attrs = wp_parse_args(
			$attrs,
			[
				'backgroundColor' => '',
				'className'       => '',
				'dividerIcon'     => 'none',
				'hideLines'       => false,
				'orientation'     => 'horizontal',
				'quantity'        => 2,
			],
		);

		$classes = classnames(
			'linksWithIcons-group',
			sprintf( 'is-%s', $attrs['orientation'] ),
			sprintf( 'has-%s-items', $attrs['quantity'] ),
			$attrs['className'],
			[
				'has-background' => $attrs['backgroundColor'],
				'has-no-lines'   => $attrs['hideLines'],
			],
			[
				sprintf( 'has-%s-background-color', $attrs['backgroundColor'] ) => $attrs['backgroundColor'],
				sprintf( 'icon-%s', $attrs['dividerIcon'] ) => $attrs['dividerIcon'],
			]
		);

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( $classes ),
			wp_kses_post( $content )
		);
	}
}
