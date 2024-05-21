<?php

// phpcs:disable Universal.FunctionDeclarations.NoLongClosures.ExceedsMaximum
$slide_label = function ( array $slide ) use ( $block ): string {
	if ( isset( $slide['topics'][0]->name ) ) {
		return $slide['topics'][0]->name;
	}

	if ( (bool) $slide['title'] ) {
		return $slide['title'];
	}

	if ( isset( $block['innerBlocks'][0]['topics'][0]->name ) ) {
		return $block['innerBlocks'][0]['topics'][0]->name;
	}

	return amnesty_get_prominent_term( get_the_ID() ?: 0 )?->name ?? '';
};
// phpcs:enable Universal.FunctionDeclarations.NoLongClosures.ExceedsMaximum

?>
<div id="slider-<?php echo esc_attr( $attrs['sliderId'] ); ?>" class="<?php echo esc_attr( classnames( $attrs['className'], 'slider' ) ); ?>">
<?php if ( $attrs['sliderTitle'] ) : ?>
	<div class="slider-title"><?php echo esc_html( $attrs['title'] ); ?></div>
<?php endif; ?>
	<div class="slides-container">
		<?php amnesty_render_slider_styles( $attrs, $attrs['sliderId'] ); ?>

		<div class="slides">
			<?php echo wp_kses_post( $content ); ?>
		</div>

	<?php if ( $attrs['hasArrows'] ) : ?>
		<button class="slides-arrow slides-arrow--next" aria-hidden="true"><?php /* translators: [front] ARIA https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ */ esc_html_e( 'Next', 'amnesty' ); ?></button>
		<button class="slides-arrow slides-arrow--previous" aria-hidden="true"><?php /* translators: [front] ARIA https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ */ esc_html_e( 'Previous', 'amnesty' ); ?></button>
	<?php endif; ?>

	<?php if ( $attrs['showTabs'] ) : ?>
		<div class="slider-navContainer" aria-hidden="true">
			<nav class="slider-nav">
			<?php foreach ( $block->innerBlocks as $index => $slide ) : // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- it's a WP object ?>
				<button class="slider-navButton" key="<?php echo esc_attr( $slide_label( $slide ) ); ?>" data-slide-index="<?php echo esc_attr( $index ); ?>">
					<?php echo esc_html( $slide_label( $slide ) ); ?>
				</button>
			<?php endforeach; ?>
			</nav>
		</div>
	<?php endif; ?>
	</div>
</div>
