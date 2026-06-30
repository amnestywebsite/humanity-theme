<?php

declare( strict_types = 1 );

add_action( 'wp_enqueue_scripts', 'amnesty_register_dompurify_script' );
add_filter( 'register_block_type_args', 'amnesty_add_dompurify_to_block_dependencies', 10, 2 );

if ( ! function_exists( 'amnesty_register_dompurify_script' ) ) {
	/**
	 * Registers DOMPurify library for use
	 *
	 * @return void
	 */
	function amnesty_register_dompurify_script(): void {
		wp_register_script(
			'dompurify',
			'https://unpkg.com/dompurify@3.4.11/dist/purify.min.js',
			[],
			'3.4.11',
			[ 'crossorigin' => 'anonymous' ],
		);
	}
}

if ( ! function_exists( 'amnesty_add_dompurify_to_block_dependencies' ) ) {
	/**
	 * Register DOMPurify as a required script dependency for block(s)
	 *
	 * @param array<string,mixed> $args the block's registration args
	 * @param string              $type the block name
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_add_dompurify_to_block_dependencies( array $args, string $type ): array {
		static $block_types = [ 'core/footnotes' ];

		if ( ! in_array( $type, $block_types, true ) ) {
			return $args;
		}

		if ( ! isset( $args['script_handles'] ) ) {
			$args['script_handles'] = [];
		}

		if ( ! is_array( $args['script_handles'] ) ) {
			$args['script_handles'] = (array) $args['script_handles'];
		}

		$args['script_handles'][] = 'dompurify';

		return $args;
	}
}
