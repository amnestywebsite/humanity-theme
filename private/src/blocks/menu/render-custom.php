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
	<button data-slider-prev disabled><?php esc_html_e( 'Previous', 'amnesty' ); ?></button>
	<button data-slider-next><?php esc_html_e( 'Next', 'amnesty' ); ?></button>
</section>
