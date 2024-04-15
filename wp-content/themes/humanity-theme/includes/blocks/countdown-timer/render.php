<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_countdown_block' ) ) {
	/**
	 * Render a countdown timer block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attrs the block attributes
	 *
	 * @return string|null
	 */
	function amnesty_render_countdown_block( $attrs = [] ) {
		$attrs = apply_filters( 'amnesty_countdown_block_attributes', $attrs );

		return sprintf(
			'<div class="clockdiv" style= text-align:%s;>
			  <div class="countdownClock" data-timestamp=%s data-expiredText="%s" />
			</div>',
			esc_attr( $attrs['alignment'] ),
			esc_attr( $attrs['date'] ),
			esc_attr( $attrs['expiredText'] )
		);
	}
}
