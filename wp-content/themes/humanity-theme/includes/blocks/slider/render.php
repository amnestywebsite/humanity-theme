<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_slider_buttons' ) ) {
	/**
	 * Render buttons for a slider block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function amnesty_render_slider_buttons() {
		?>
		<button class="slides-arrow slides-arrow--next" aria-hidden="true"><?php /* translators: [front] ARIA https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ */ esc_html_e( 'Next', 'amnesty' ); ?></button>
		<button class="slides-arrow slides-arrow--previous" aria-hidden="true"><?php /* translators: [front] ARIA https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ */ esc_html_e( 'Previous', 'amnesty' ); ?></button>
		<?php
	}
}

if ( ! function_exists( 'amnesty_render_slider_tabs' ) ) {
	/**
	 * Render navigation tabs for a slider block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $slides the list of slides in the slider
	 *
	 * @return void
	 */
	function amnesty_render_slider_tabs( array $slides ) {
		print '<div class="slider-navContainer" aria-hidden="true"><nav class="slider-nav">';

		// if no topic then grab one
		if ( empty( $slides[0]['topics'][0]->name ) && get_the_ID() ) {
			$fallback_topic = amnesty_get_prominent_term( get_the_ID() );
		}

		foreach ( $slides as $index => $slide ) {
			if ( ! empty( $slide['topics'][0]->name ) ) {
				printf( '<button class="slider-navButton" key="%1$s" data-slide-index="%2$s">%1$s</button>', esc_html( $slide['topics'][0]->name ), esc_html( $index ) );
			} elseif ( ! empty( $slide['title'] ) && empty( $slide['topics'][0]->name ) ) {
				printf( '<button class="slider-navButton" key="%1$s" data-slide-index="%2$s">%1$s</button>', esc_html( $slide['title'] ), esc_html( $index ) );
			} elseif ( ! empty( $fallback_topic->name ) ) {
				printf( '<button class="slider-navButton" key="%1$s" data-slide-index="%2$s">%1$s</button>', esc_html( $fallback_topic->name ), esc_html( $index ) );
			}
		}

		print '</nav></div>';
	}
}

if ( ! function_exists( 'amnesty_render_slider_item' ) ) {
	/**
	 * Render the current slider item.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array   $data - slide data.
	 * @param string  $slider_id - slide id.
	 * @param boolean $has_content - if content is available.
	 *
	 * @return void
	 *
	 * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	 */
	function amnesty_render_slider_item( $data, $slider_id, $has_content ) {
		// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$data = wp_parse_args(
			$data,
			[
				'alignment'        => '',
				'background'       => '',
				'callToActionLink' => '',
				'callToActionText' => '',
				'content'          => '',
				'heading'          => '',
				'hideContent'      => '',
				'id'               => '',
				'imageId'          => 0,
				'subheading'       => '',
				'timelineContent'  => '',
			] 
		);

		$id               = $data['id'];
		$alignment        = $data['alignment'];
		$background       = $data['background'];
		$timeline_content = $data['timelineContent'];
		$hide_content     = $data['hideContent'];
		$title            = $data['heading'];
		$subtitle         = $data['subheading'];
		$cta_text         = $data['callToActionText'];
		$cta_link         = $data['callToActionLink'];
		$content          = $data['content'];
		$show_cta_btn     = false;
		$show_toggle      = false;
		$has_inner        = false;
		$image_id         = absint( $data['imageId'] );

		if ( '' !== $cta_text && '' !== $cta_link ) {
			$show_cta_btn = true;
		}

		if ( $show_cta_btn || '' !== $content ) {
			$show_toggle = true;
		}

		if ( $show_cta_btn || $show_toggle ) {
			$has_inner = true;
		}

		require realpath( __DIR__ . '/views/slide.php' );
	}
}

if ( ! function_exists( 'amnesty_render_slider_styles' ) ) {
	/**
	 * Render the <style> tag for the slider
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array  $attributes the block attributes
	 * @param string $slider_id  the block identifier
	 *
	 * @return void
	 */
	function amnesty_render_slider_styles( array $attributes, string $slider_id ): void {
		$slides = $attributes['slides'] ?? [];

		if ( 0 === count( $slides ) ) {
			return;
		}

		echo '<style class="aiic-ignore">';

		foreach ( $slides as $slide ) {
			if ( ! isset( $slide['id'] ) ) {
				continue;
			}

			$image_id    = $slide['imageId'] ?? '';
			$small_image = wp_get_attachment_image_url( $image_id, 'hero-sm' );
			$large_image = wp_get_attachment_image_url( $image_id, 'hero-lg' );

			printf(
				'#slider-%1$s #slide-%2$s{background-image:url("%3$s")}@media screen and (min-width:1444px){#slider-%1$s #slide-%2$s{background-image:url("%4$s")}}',
				esc_attr( $slider_id ),
				esc_attr( $slide['id'] ),
				esc_url( $small_image ),
				esc_url( $large_image )
			);
		}

		echo '</style>';
	}
}

if ( ! function_exists( 'amnesty_render_block_slider' ) ) {
	/**
	 * Render a slider block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_block_slider( array $attributes = [] ) {
		$attributes = wp_parse_args(
			$attributes,
			[
				'hasArrows'            => true,
				'hasContent'           => false,
				'showTabs'             => true,
				'sliderId'             => '',
				'sliderTitle'          => '',
				'slides'               => [],
				'timelineCaptionStyle' => '',
			] 
		);

		$slider_id     = $attributes['sliderId'];
		$caption_style = $attributes['timelineCaptionStyle'];
		$slider_title  = $attributes['sliderTitle'];
		$slides        = $attributes['slides'];
		$has_content   = $attributes['hasContent'];
		$has_arrows    = $attributes['hasArrows'];
		$show_tabs     = $attributes['showTabs'];

		ob_start();
		printf( '<div id="slider-%1$s" class="slider timeline-%2$s">', esc_html( $slider_id ), esc_html( $caption_style ) );

		if ( $slider_title ) {
			printf( '<div class="slider-title">%1$s</div>', esc_html( $slider_title ) );
		}

		print '<div class="slides-container">';
		amnesty_render_slider_styles( $attributes, $slider_id );
		print '<div class="slides">';
		foreach ( $slides as $slide ) {
			amnesty_render_slider_item( $slide, $slider_id, $has_content );
		}
		print '</div>';

		if ( $has_arrows ) {
			amnesty_render_slider_buttons();
		}

		if ( $show_tabs ) {
			amnesty_render_slider_tabs( $slides );
		}

		print '</div>'; // /.slides-container
		print '</div>';// /.slider

		return ob_get_clean();
	}
}
