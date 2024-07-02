<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_pop_in_block' ) ) {
	/**
	 * Render the pop-in block
	 *
	 * @param array<string,mixed> $attributes block attributes
	 * @param string              $content    block content
	 *
	 * @return string
	 */
	function render_pop_in_block( array $attributes = [], string $content = '' ): string {
		if ( ! $content ) {
			return '';
		}

		$pop_in  = '<aside id="pop-in" class="u-textCenter pop-in is-closed">';
		$pop_in .= '<button id="pop-in-close" class="pop-in-close">X</button>';
		$pop_in .= '<div class="section section--small">';
		$pop_in .= '<div class="container container--small">';
		$pop_in .= $content;
		$pop_in .= '</div>';
		$pop_in .= '</div>';
		$pop_in .= '</aside>';

		return $pop_in;
	}
}
