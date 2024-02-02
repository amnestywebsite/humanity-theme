<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_menu_block_wp_nav' ) ) {
	/**
	 * Render a wp_nav_menu within a Menu block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $atts the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_menu_block_wp_nav( array $atts = [] ) {
		$menu = wp_get_nav_menu_object( absint( $atts['menuId'] ) );

		spaceless();

		?>

		<section class="postlist-categoriesContainer <?php empty( $atts['color'] ) || printf( 'section--%s', esc_attr( $atts['color'] ) ); ?>" data-slider>
			<nav>
				<ul class="postlist-categories<?php $menu->count > 4 && print ' use-flickity'; ?>" aria-label="<?php /* translators: [front] ARIA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'Category List', 'amnesty' ); ?>">
					<?php

					wp_nav_menu(
						[
							'menu'            => $atts['menuId'],
							'container'       => false,
							'container_class' => 'menu-{menu slug}-container',
							'container_id'    => '',
							'menu_class'      => 'menu',
							'menu_id'         => 'category_style_menu',
							'echo'            => true,
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '%3$s',
							'depth'           => 1,
						]
					);

					?>
				</ul>
			</nav>
			<button data-slider-prev disabled>Previous</button>
			<button data-slider-next>Next</button>
		</section>

		<?php

		return endspaceless( false );
	}
}

if ( ! function_exists( 'amnesty_render_menu_block_in_page' ) ) {
	/**
	 * Render an "in-page" menu block, generated from
	 * section blocks that have non-empty id attributes
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $atts the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_menu_block_in_page( array $atts = [] ) {
		$blocks   = parse_blocks( get_post_field( 'post_content' ) );
		$sections = array_filter(
			$blocks,
			// phpcs:ignore Universal.FunctionDeclarations.NoLongClosures.ExceedsRecommended
			function ( $block ) {
				if ( 'amnesty-core/block-section' !== $block['blockName'] ) {
					return false;
				}

				if ( empty( $block['attrs']['sectionId'] ) || empty( $block['attrs']['sectionName'] ) ) {
					return false;
				}

				return true;
			}
		);

		if ( empty( $sections ) ) {
			return '';
		}

		spaceless();

		?>

		<section class="postlist-categoriesContainer <?php empty( $atts['color'] ) || printf( 'section--%s', esc_attr( $atts['color'] ) ); ?>" data-slider>
			<nav>
				<ul class="postlist-categories<?php count( $sections ) > 4 && print ' use-flickity'; ?>" aria-label="<?php /* translators: [front] ARIA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'List of page sections', 'amnesty' ); ?>">
				<?php

				foreach ( $sections as $section ) {
					printf( '<li><a class="btn btn--white" href="#%s">%s</a></li>', esc_attr( $section['attrs']['sectionId'] ), esc_html( $section['attrs']['sectionName'] ) );
				}

				?>
				</ul>
			</nav>
			<button data-slider-prev disabled>Previous</button>
			<button data-slider-next>Next</button>
		</section>

		<?php

		return endspaceless( false );
	}
}

if ( ! function_exists( 'amnesty_render_menu_block_custom' ) ) {
	/**
	 * Render an "in-page" menu block, generated from
	 * manual attribute specification
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $atts the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_menu_block_custom( array $atts = [] ) {
		spaceless();

		?>

		<section class="postlist-categoriesContainer <?php empty( $atts['color'] ) || printf( 'section--%s', esc_attr( $atts['color'] ) ); ?>" data-slider>
			<nav>
				<ul class="postlist-categories<?php count( $atts['items'] ) > 4 && print ' use-flickity'; ?>" aria-label="<?php /* translators: [front] ARIA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'List of page sections', 'amnesty' ); ?>">
				<?php

				foreach ( $atts['items'] as $item ) {
					printf( '<li><a class="btn btn--white" href="#%s">%s</a></li>', esc_attr( $item['id'] ), esc_html( $item['label'] ) );
				}

				?>
				</ul>
			</nav>
			<button data-slider-prev disabled>Previous</button>
			<button data-slider-next>Next</button>
		</section>

		<?php

		return endspaceless( false );
	}
}

if ( ! function_exists( 'amnesty_render_menu_block' ) ) {
	/**
	 * Render a menu block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block attributes
	 *
	 * @return string
	 */
	function amnesty_render_menu_block( $attributes = [] ) {
		$atts = wp_parse_args(
			$attributes,
			[
				'menuId' => false,
				'color'  => '',
				'type'   => 'standard-menu',
			]
		);

		if ( 'standard-menu' === $atts['type'] ) {
			if ( ! $atts['menuId'] ) {
				return '';
			}

			return amnesty_render_menu_block_wp_nav( $atts );
		}

		if ( 'inpage-menu' === $atts['type'] ) {
			return amnesty_render_menu_block_in_page( $atts );
		}

		if ( 'custom-menu' === $atts['type'] && ! empty( $atts['items'] ) ) {
			return amnesty_render_menu_block_custom( $atts );
		}

		return '';
	}
}
