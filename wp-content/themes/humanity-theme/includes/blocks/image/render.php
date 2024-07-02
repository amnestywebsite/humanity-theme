<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_image_block' ) ) {
	/**
	 * Render the Amnesty Image Block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function render_image_block( array $attributes = [] ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'align'      => 'default',
				'buttons'    => [],
				'caption'    => '',
				'content'    => '',
				'hasOverlay' => false,
				'identifier' => amnesty_rand_str( 4 ),
				'imageID'    => 0,
				'imageURL'   => '',
				'parallax'   => false,
				'style'      => 'loose',
				'title'      => '',
				'type'       => '',
				'videoID'    => 0,
				'videoURL'   => '',
			] 
		);

		// used in views
		$block_classes = classnames(
			'imageBlock',
			[
				sprintf( 'imageBlock-%s', esc_attr( $attributes['identifier'] ) ) => (bool) $attributes['parallax'],
				'imageBlock--fixed' => 'fixed' === $attributes['style'],
				'has-video'         => 'video' === $attributes['type'],
				'has-parallax'      => (bool) $attributes['parallax'],
			] 
		);

		// used in views
		$caption_classes = classnames(
			'imageBlock-caption',
			[
				sprintf( 'align%s', $attributes['align'] ) => 'default' !== $attributes['align'],
			] 
		);

		if ( $attributes['parallax'] ) {
			spaceless();
			require realpath( __DIR__ . '/views/parallax.php' );
			return endspaceless( false );
		}

		// the srcset declaration, for the fixed height style, causes much larger images to load than is necessary
		$remove_srcset = function ( array $props ) use ( $attributes ): array {
			if ( 'fixed' !== $attributes['style'] ) {
				return $props;
			}

			unset( $props['srcset'], $props['sizes'] );
			return $props;
		};

		add_filter( 'wp_get_attachment_image_attributes', $remove_srcset );
		spaceless();
		require realpath( __DIR__ . '/views/block.php' );
		$block = endspaceless( false );
		remove_filter( 'wp_get_attachment_image_attributes', $remove_srcset );

		if ( 'default' === $attributes['align'] ) {
			return $block;
		}

		$wrapper_classes = classnames(
			'imageBlock-wrapper',
			'u-cf',
			[
				sprintf( 'align%s', $attributes['align'] ) => 'default' !== $attributes['align'],
			] 
		);

		return sprintf( '<div class="%s">%s</div>', esc_attr( $wrapper_classes ), $block );
	}
}
