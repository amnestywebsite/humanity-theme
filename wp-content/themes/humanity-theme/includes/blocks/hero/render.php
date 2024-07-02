<?php

declare( strict_types = 1 );

use Amnesty\Get_Image_Data;

if ( ! function_exists( 'render_hero_block' ) ) {
	/**
	 * Render a hero block
	 *
	 * @param array  $attributes the block attributes
	 * @param string $content    the block inner content
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return string
	 *
	 * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- used in view
	 */
	function render_hero_block( array $attributes = [], string $content = '' ): string {
		// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$attrs = wp_parse_args(
			$attributes,
			[
				'align'            => '',
				'background'       => '',
				'className'        => 'wp-block-amnesty-core-hero',
				'content'          => '',
				'ctaLink'          => '',
				'ctaText'          => '',
				'featuredImageId'  => 0,
				'featuredVideoId'  => 0,
				'hideImageCaption' => true,
				'hideImageCredit'  => false,
				'imageID'          => 0, // this overrides the featured image
				'title'            => '',
				'type'             => 'image',
			]
		);

		// precedence: override -> featured image attribute -> featured image meta
		$image_id = $attrs['imageID'] ?: $attrs['featuredImageId'] ?: get_post_thumbnail_id();

		$image = new Get_Image_Data( (int) $image_id );
		$video = new Get_Image_Data( (int) $attrs['featuredVideoId'] );

		$video_output = '';
		// If the block has a featured video, get the video URL
		if ( $attrs['featuredVideoId'] && 'video' === $attrs['type'] ) {
			// $video_output used in hero.php view
			$video_output .= sprintf(
				'<div class="hero-videoContainer">
					<video class="hero-video" autoplay loop muted>
						<source src="%s">
					</video>
				</div>',
				esc_url( wp_get_attachment_url( $attrs['featuredVideoId'] ) ),
			);
		}

		// Build output for the image/video caption and credit
		// $media_meta_output used in hero.php view
		// Reverse the boolean value of the arguments to match the value of the arguments in the function
		$media_meta_output  = $image->metadata( ! $attrs['hideImageCaption'], ! $attrs['hideImageCredit'], 'image' );
		$media_meta_output .= $video->metadata( ! $attrs['hideImageCaption'], ! $attrs['hideImageCredit'], 'video' );

		spaceless();
		require realpath( __DIR__ . '/views/hero.php' );
		return endspaceless( false );
	}
}
