<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_collapsable_block' ) ) {
	/**
	 * Render a collapsable block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes The block attributes
	 * @param string              $content    The block content
	 *
	 * @return string
	 */
	function render_collapsable_block( array $attributes, string $content = '' ): string {
		$attrs = wp_parse_args(
			$attributes,
			[
				'anchor'    => '',
				'collapsed' => false,
				'title'     => '',
			]
		);

		if ( ! $content ) {
			// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable -- used in view
			$content = '';
		}

		$wrapper_attrs = [];

		if ( $attrs['collapsed'] ) {
			$wrapper_attrs['class'] = 'is-collapsed';
		}

		ob_start();
		require realpath( __DIR__ . '/views/block.php' );
		return ob_get_clean();
	}
}
