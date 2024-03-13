<?php

declare( strict_types = 1 );

if ( ! function_exists( 'trim_text' ) ) {
	/**
	 * Trims text to a space then adds ellipses if desired.
	 *
	 * @package Amnesty
	 *
	 * @param string $input       text to trim.
	 * @param int    $length      in characters to trim to.
	 * @param bool   $ellipses    if ellipses (...) are to be added.
	 * @param bool   $strip_html  if html tags are to be stripped.
	 * @param bool   $strip_style if css style are to be stripped.
	 *
	 * @return string
	 */
	function trim_text( $input, $length, $ellipses = true, $strip_html = true, $strip_style = true ) {
		if ( $strip_html ) {
			$input = wp_strip_all_tags( $input );
		}

		if ( $strip_style ) {
			$input = preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $input );
		}

		if ( 'full' === $length ) {
			return $input;
		}

		// no need to trim, already shorter than trim length.
		if ( mb_strlen( $input, 'UTF-8' ) <= $length ) {
			return $input;
		}

		// find last space within length.
		$last_space   = mb_strrpos( mb_substr( $input, 0, $length, 'UTF-8' ), ' ', 0, 'UTF-8' ) ?: $length;
		$trimmed_text = mb_substr( $input, 0, $last_space, 'UTF-8' );

		// add ellipses.
		if ( $ellipses ) {
			$trimmed_text .= '...';
		}

		return $trimmed_text;
	}
}

if ( ! function_exists( 'remove_empty_p' ) ) {
	/**
	 * Remove empty p tags from WordPress posts.
	 *
	 * @package Amnesty
	 *
	 * @author https://gist.github.com/ninnypants/1668216
	 *
	 * @param string - $content - Current content.
	 *
	 * @return string
	 */
	function remove_empty_p( $content = '' ) {
		$content = preg_replace(
			[
				'#<p>\s*<(div|aside|section|article|header|footer)#',
				'#</(div|aside|section|article|header|footer)>\s*</p>#',
				'#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
				'#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
				'#<p>\s*</(div|aside|section|article|header|footer)#',
			],
			[
				'<$1',
				'</$1>',
				'</$1>',
				'<$1$2>',
				'</$1',
			],
			$content
		);

		return preg_replace( '#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content );
	}
}

add_filter( 'the_content', 'remove_empty_p', 20, 1 );

if ( ! function_exists( 'remove_duplicate_image' ) ) {
	/**
	 * Removes the first image from the content if it
	 * is the same as the featured image.
	 *
	 * @package Amnesty
	 *
	 * @param string $post_content Current content.
	 *
	 * @return string
	 */
	function remove_duplicate_image( $post_content ) {
		$featured_image_id = get_post_thumbnail_id();
		$featured_image    = wp_get_attachment_image_url( $featured_image_id, 'full' );

		$first_line = strtok( $post_content, "\n" );

		if ( ! $first_line ) {
			return $post_content;
		}

		preg_match( '/(https?:\/\/.*\.(?:png|jpg|jpeg|gif))/i', $first_line, $matches );

		if ( isset( $matches[0] ) && $matches[0] === $featured_image ) {
			$content_array = explode( "\n", $post_content );

			$post_content = implode( "\n", array_slice( $content_array, 1 ) );
		}

		return $post_content;
	}
}

add_filter( 'the_content', 'remove_duplicate_image', 20, 1 );

/**
 * Remove empty secctions from the content.
 * This is to adjust for user-error if empty section gutenblocks are added to the content.
 */
add_filter(
	'the_content',
	function ( $content = '' ) {
		$filtered = preg_replace( '/<section[^>]+><div(?:\s+id="[^"]*")? class="container">\s*?(?R)?\s*?<\/div><\/section>/', '', $content );

		return $filtered;
	},
	20
);
