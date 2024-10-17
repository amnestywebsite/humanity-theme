<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_find_first_block_of_type' ) ) {
	/**
	 * Retrieve header block from an array of parsed blocks
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<int,array<string,mixed>> $blocks     the parsed blocks
	 * @param string                         $block_name the block to find
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_find_first_block_of_type( array $blocks = [], string $block_name = '' ): array {
		foreach ( $blocks as $block ) {
			if ( $block['blockName'] === $block_name ) {
				return $block;
			}

			if ( empty( $block['innerBlocks'] ) ) {
				continue;
			}

			return amnesty_find_first_block_of_type( $block['innerBlocks'], $block_name );
		}

		return [];
	}
}

if ( ! function_exists( 'amnesty_string_to_paragraphs' ) ) {
	/**
	 * Convert a string of text to paragraph block markup
	 *
	 * @param string $content the content to transform
	 *
	 * @return string
	 */
	function amnesty_string_to_paragraphs( string $content ): string {
		$content = wpautop( $content );
		$content = explode( '</p>', $content );
		$content = array_filter( array_map( 'trim', $content ) );
		$output  = '';

		foreach ( $content as $paragraph ) {
			$output .= PHP_EOL;
			$output .= '<!-- wp:paragraph -->';
			$output .= PHP_EOL;
			$output .= $paragraph . '</p>';
			$output .= PHP_EOL;
			$output .= '<!-- /wp:paragraph -->';
			$output .= PHP_EOL;
		}

		return trim( $output );
	}
}
