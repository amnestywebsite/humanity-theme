<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_custom_card_block' ) ) {
	/**
	 * Render the Custom Card block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $attributes the block attributes
	 *
	 * @return string
	 */
	function render_custom_card_block( array $attributes ): string {
		$attributes = wp_parse_args(
			$attributes,
			[
				'align'         => '',
				'centred'       => false,
				'className'     => '',
				'content'       => '',
				'imageAlt'      => '',
				'imageID'       => 0,
				'imageURL'      => '',
				'label'         => '',
				'largeImageURL' => '',
				'link'          => '',
				'linkText'      => '',
				'scrollLink'    => '',
				'style'         => 'standard',
			] 
		);

		// used in view
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$block_classes = classnames(
			'customCard',
			$attributes['className'],
			[
				sprintf( 'align%s', $attributes['align'] ) => (bool) $attributes['align'],
				'actionBlock--wide' => 'wide' === $attributes['style'],
				'is-centred'        => (bool) $attributes['centred'],
			] 
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		// used in view
		$button_classes = classnames( 'btn', 'btn--fill', 'btn--large' );

		spaceless();
		require __DIR__ . '/views/card.php';
		return endspaceless( false );
	}
}
