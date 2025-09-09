<?php

if ( ! function_exists( 'add_alignment_controls_to_list_block' ) ) {
	/**
	 * * Add alignment support to the core/list block
	 *
	 * @param array<string,mixed> $metadata The block type metadata
	 *
	 * @return array<string,mixed> $metadata The modified block type metadata
	 */
	function add_alignment_controls_to_list_block( array $metadata ): array {
		if ( 'core/list' === $metadata['name'] ) {
			$metadata['supports']['align'] = true;
		}
		return $metadata;
	}
}

add_filter( 'block_type_metadata', 'add_alignment_controls_to_list_block' );
