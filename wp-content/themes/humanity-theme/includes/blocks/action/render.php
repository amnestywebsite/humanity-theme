<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_action_block' ) ) {
	/**
	 * Render the Action block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function render_action_block( array $attributes ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'centred'       => false,
				'content'       => '',
				'imageAlt'      => '',
				'imageID'       => 0,
				'imageURL'      => '',
				'label'         => '',
				'largeImageURL' => '',
				'link'          => '',
				'linkText'      => '',
				'style'         => 'standard',
			] 
		);

		spaceless();

		if ( 'wide' === $attributes['style'] ) {
			require realpath( __DIR__ . '/views/wide.php' );
			return endspaceless( false );
		}

		require realpath( __DIR__ . '/views/standard.php' );
		return endspaceless( false );
	}
}
