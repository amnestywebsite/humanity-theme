<?php

/**
 * Title: Pagination
 * Description: Post pagination
 * Slug: amnesty/pagination
 * Inserter: yes
 */

/* translators: [front] https://www.amnesty.org/en/latest/ Previous post navigation label */
$previous_link = get_previous_posts_link( '<span class="icon"></span><span>' . __( 'Previous', 'amnesty' ) . '</span>' );
/* translators: [front] https://www.amnesty.org/en/latest/ Next post navigation label */
$next_link    = get_next_posts_link( '<span class="icon"></span><span>' . __( 'Next', 'amnesty' ) . '</span>' );
$page_numbers = amnesty_paginate_links(
	[
		'mid_size'  => 1,
		'prev_next' => false,
		'type'      => 'array',
	]
);

if ( empty( $page_numbers ) ) {
	return;
}

?>
<!-- wp:group {"tagName":"section","className":"section section--small post-pagination"} -->
<section class="wp-block-group section section--small post-pagination">
	<!-- wp:group {"tagName":"nav","className":"post-paginationContainer","ariaLabel":"<?php echo esc_attr( __( 'Pagination', 'amnesty' ) ); ?>"} -->
	<nav class="wp-block-group post-paginationContainer" aria-label="<?php echo esc_attr( __( 'Pagination', 'amnesty' ) ); ?>">
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationPrevious"} -->
		<div class="wp-block-group post-paginationLink post-paginationPrevious">
		<?php if ( $previous_link ) : ?>
			<?php echo wp_kses_post( $previous_link ); ?>
		<?php else : ?>
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"tagName":"button","type":"button"} -->
				<div class="wp-block-button">
					<button type="button" class="wp-block-button__link wp-element-button" disabled>
						<span><?php /* translators: [front] https://www.amnesty.org/en/latest/ */ esc_html_e( 'Previous', 'amnesty' ); ?></span>
						<span class="icon"></span>
					</button>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
		<!-- wp:list -->
		<ul class="wp-block-list page-numbers" aria-label="<?php /* translators: [front] AIRA https://www.amnesty.org/en/latest/ */ esc_attr_e( 'Page numbers', 'amnesty' ); ?>">
		<?php foreach ( $page_numbers as $number ) : ?>
			<!-- wp:list-item -->
			<li class="wp-block-list-item"><?php echo wp_kses_post( $number ); ?></li>
			<!-- /wp:list-item -->
		<?php endforeach; ?>
		</ul>
		<!-- /wp:list -->
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationNext"} -->
		<div class="post-paginationLink post-paginationNext">
		<?php if ( $next_link ) : ?>
			<?php echo wp_kses_post( $next_link ); ?>
		<?php else : ?>
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"tagName":"button","type":"button"} -->
				<div class="wp-block-button">
					<button type="button" class="wp-block-button__link wp-element-button" disabled>
						<span><?php /* translators: [front] https://www.amnesty.org/en/latest/ */ esc_html_e( 'Previous', 'amnesty' ); ?></span>
						<span class="icon"></span>
					</button>
				</div>
			<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</nav>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
