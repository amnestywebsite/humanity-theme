<?php

declare( strict_types = 1 );

use Amnesty\Get_Image_Data;

if ( ! function_exists( 'amnesty_build_new_image_block_tag' ) ) {
	/**
	 * Check whether an image block should have image metadata added
	 *
	 * @param array          $block     the parsed block
	 * @param array          $image_tag img tag regex result
	 * @param Get_Image_Data $image_obj object representing the image
	 * @param bool           $wrap      whether to wrap the output
	 *
	 * @return string
	 */
	function amnesty_build_new_image_block_tag( array $block, array $image_tag, Get_Image_Data $image_obj, bool $wrap = true ): string {
		$new_image_tag = match ( str_contains( $image_tag[0], 'class' ) ) {
			true  => str_replace( 'class="', 'class="aiic-ignore ', $image_tag[0] ),
			false => str_replace( '<img ', '<img class="aiic-ignore" ', $image_tag[0] ),
		};

		$hide_copyright = ( $block['attrs']['hideImageCopyright'] ?? false );
		$hide_caption   = ( $block['attrs']['hideImageCaption'] ?? true );

		if ( str_contains( $block['attrs']['className'] ?? '', 'article-figure' ) ) {
			$hide_caption = '1' === get_post_meta( get_the_ID(), '_hide_featured_image_caption', true );
		}

		$new_image_tag .= $image_obj->metadata( ! $hide_caption, ! $hide_copyright );

		if ( false === $wrap ) {
			return $new_image_tag;
		}

		return '<div style="position:relative">' . $new_image_tag . '</div>';
	}
}

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
		if ( 'core/image' !== ( $block['blockName'] ?? null ) || ! isset( $block['attrs']['id'] ) ) {
			return $content;
		}

		preg_match( '/<figure[^>].*?>/', $content, $opening_tag );
		preg_match( '/<img[^>]*?>/', $content, $image_tag );

		// couldn't find html tags
		if ( ! isset( $opening_tag[0], $image_tag[0] ) ) {
			return $content;
		}

		$image_object = new Get_Image_Data( $block['attrs']['id'] );

		if ( $image_object->credit() ) {
			$new_opening_tag = str_replace( 'class="', 'class="aimc-ignore ', $opening_tag );
			$content         = str_replace( $opening_tag, $new_opening_tag, $content );
		}

		$should_not_wrap = ! str_contains( $block['attrs']['className'] ?? '', 'article-figure' );
		$new_image_tag   = amnesty_build_new_image_block_tag( $block, $image_tag, $image_object, wrap: $should_not_wrap );

		$content = str_replace( $image_tag[0], $new_image_tag, $content );

		return $content;
	}
}

add_filter( 'render_block', 'amnesty_add_image_metadata_to_image_block', 100, 2 );
