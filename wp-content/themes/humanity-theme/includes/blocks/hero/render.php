<?php

declare( strict_types = 1 );

use Amnesty\Get_Image_Data;

if ( ! function_exists( 'render_hero_block' ) ) {
	/**
	 * Render a hero block
	 *
	 * @param array  $attributes the block attributes
	 * @param string $content    the block inner content
	 * @param string $name       the block name
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return string
	 *
	 * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
	 * $name is used in the hero.php view
	 */
	function render_hero_block( array $attributes = [], string $content = '', $name = '' ): string {
		$attrs = wp_parse_args(
			$attributes,
			[
				'hideImageCaption' => true,
				'hideImageCredit'  => false,
				'title'            => '',
				'content'          => '',
				'ctaText'          => '',
				'ctaLink'          => '',
				'align'            => '',
				'background'       => '',
				'type'             => '',
				'imageID'          => 0,
				'imageURL'         => '',
				'featuredVideoId'  => 0,
			]
		);

		$image_id = $attrs['imageID'];
		if ( ! $image_id ) {
			// Fall back to the featured image ID
			$image_id = get_post_thumbnail_id();
		}
		$image_url = $attrs['imageURL'];
		if ( ! $image_url ) {
			// Fall back to the featured image URL
			// $image_url used in hero.php view
			$image_url = get_the_post_thumbnail_url();
		}

		// Get image credit markup
		$image = new Get_Image_Data( $image_id );

		// Define $video_output before it is used to prevent warnings
		$video_output = '';

		// Define $image_meta_output before it is used to prevent warnings
		$image_meta_output = '';

		// If the block has a featured video, get the video URL
		if ( $attrs['featuredVideoId'] && 'video' === $attrs['type'] ) {
			// $video_output used in hero.php view
			$video_output .= sprintf(
				'<div class="headerBackgroundVideo">
					<video class="headerVideo" autoplay loop muted>
						<source src="%s" />
					</video>
				</div>',
				esc_url( wp_get_attachment_url( $attrs['featuredVideoId'] ) )
			);
		} elseif ( 'video' !== $attrs['type'] ) {
			// Build output for the image caption and credit
			// $image_meta_output used in hero.php view
			// Reverse the boolean value of the arguments to match the value of the arguments in the function
			$image_meta_output .= $image->metadata( ! $attrs['hideImageCaption'], ! $attrs['hideImageCredit'] );
		}

		// Define $inner_blocks before it is used to prevent warnings
		$inner_blocks = '';

		// If inner blocks are present, build the inner blocks
		if ( $content ) {
			// $inner_blocks used in hero.php view
			$inner_blocks .= sprintf(
				'<div class="donation">%s</div>',
				wp_kses_post( $content )
			);
		}

		spaceless();
		require realpath( __DIR__ . '/views/hero.php' );
		return endspaceless( false );
	}
}
