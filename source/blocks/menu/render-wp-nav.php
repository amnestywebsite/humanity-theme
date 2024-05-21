<?php

$the_menu = wp_get_nav_menu_object( absint( $attributes['menuId'] ) );

?>
<section class="postlist-categoriesContainer <?php empty( $attributes['color'] ) || printf( 'section--%s', esc_attr( $attributes['color'] ) ); ?>" data-slider>
	<nav>
		<ul class="postlist-categories<?php $the_menu->count > 4 && print ' use-flickity'; ?>" aria-label="<?php /* translators: [front] ARIA https://www.amnesty.org/en/latest/ */ esc_attr_e( 'Category List', 'amnesty' ); ?>">
			<?php

			wp_nav_menu(
				[
					'menu'            => $attributes['menuId'],
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
	<button data-slider-prev disabled><?php esc_html_e( 'Previous', 'amnesty' ); ?></button>
	<button data-slider-next><?php esc_html_e( 'Next', 'amnesty' ); ?></button>
</section>
