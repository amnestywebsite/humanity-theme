<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_link_group_block' ) ) {
	/**
	 * Render the Amnesty Link Group block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function render_link_group_block( array $attributes = [] ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'className' => 'wp-block-amnesty-core-link-group',
				'align'     => '',
				'title'     => '',
				'items'     => [],
			] 
		);

		$icon = '<svg viewBox="0 0 32 32"><path d="M8 20c0 0 1.838-6 12-6v6l12-8-12-8v6c-8 0-12 4.99-12 10zM22 24h-18v-12h3.934c0.315-0.372 0.654-0.729 1.015-1.068 1.374-1.287 3.018-2.27 4.879-2.932h-13.827v20h26v-8.395l-4 2.667v1.728z" /></svg>';

		ob_start();

		require realpath( __DIR__ . '/views/link-group.php' );

		return ob_get_clean();
	}
}
