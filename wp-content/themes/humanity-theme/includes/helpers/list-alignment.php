<?php

if ( ! function_exists( 'add_alignment_controls_to_list_block' ) ) {
	/**
	 * * Add alignment support to the core/list block
	 *
	 * @param array $metadata - The block type metadata
	 *
	 * @return array $metadata - The modified block type metadata
	 */
	function add_alignment_controls_to_list_block( $metadata ) {
		if ( 'core/list' === $metadata['name'] ) {
			$metadata['supports']['align'] = true;
		}
		return $metadata;
	}
}

add_filter( 'block_type_metadata', 'add_alignment_controls_to_list_block' );
