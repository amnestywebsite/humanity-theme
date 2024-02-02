<?php

declare( strict_types = 1 );

use Amnesty\Get_Image_Data;

if ( ! function_exists( 'amnesty_add_image_metadata_to_image_block' ) ) {
	/**
	 * Add image metadata to the core image block
	 *
	 * @param string $content the block content
	 * @param array  $block   the parsed block
	 *
	 * @package Amnesty\CoreBlocks
	 *
	 * @return string
	 */
	function amnesty_add_image_metadata_to_image_block( string $content, array $block ): string {
		if ( 'core/image' !== ( $block['blockName'] ?? null ) ) {
			return $content;
		}

		// can't load image data
		if ( ! isset( $block['attrs']['id'] ) ) {
			return $content;
		}

		preg_match( '/<figure[^>].*?>/', $content, $opening_tag );
		preg_match( '/<img[^>]*?>/', $content, $image_tag );

		// couldn't find html tags
		if ( ! isset( $opening_tag[0], $image_tag[0] ) ) {
			return $content;
		}

		$new_opening_tag = str_replace( 'class="', 'class="aimc-ignore ', $opening_tag );

		$image_object = new Get_Image_Data( $block['attrs']['id'] );

		$new_image_tag  = '<div style="position:relative">';
		$new_image_tag .= match ( str_contains( $image_tag[0], 'class' ) ) {
			true => str_replace( 'class="', 'class="aiic-ignore ', $image_tag[0] ),
			false => str_replace( '<img ', '<img class="aiic-ignore" ', $image_tag[0] ),
		};

		$new_image_tag .= $image_object->metadata(
			! ( $block['attrs']['hideImageCaption'] ?? true ),
			! ( $block['attrs']['hideImageCopyright'] ?? false ),
		);
		$new_image_tag .= '</div>';

		$content = str_replace( $opening_tag, $new_opening_tag, $content );
		$content = str_replace( $image_tag[0], $new_image_tag, $content );

		return $content;
	}
}

add_filter( 'render_block', 'amnesty_add_image_metadata_to_image_block', 100, 2 );
