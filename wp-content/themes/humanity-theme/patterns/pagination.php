<?php

/**
 * Title: Pagination pattern
 * Description: Pagination pattern for the theme
 * Slug: amnesty/pagination
 * Inserter: no
 */

/* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/ Previous post navigation label */
$previous_link = get_previous_posts_link( '<span class="icon"></span><span>' . __( 'Previous', 'amnesty' ) . '</span>' );
/* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/ Next post navigation label */
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

<!-- wp:group {"tagName":"div","className":"container container--small has-gutter"} -->
<div class="wp-block-group container container--small has-gutter">
	<!-- wp:group {"tagName":"section","className":"section section--small post-pagination"} -->
<section class="wp-block-group section section--small post-pagination">
	<!-- wp:group {"tagName":"nav","className":"post-paginationContainer","role":"navigation","aria-label":"<?php echo esc_attr( __( 'Pagination', 'amnesty' ) ); ?>"} -->
	<nav class="wp-block-group post-paginationContainer" role="navigation" aria-label="<?php echo esc_attr( __( 'Pagination', 'amnesty' ) ); ?>">
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationPrevious"} -->
		<div class="wp-block-group post-paginationLink post-paginationPrevious">
		<?php if ( $previous_link ) : ?>
			<?php echo wp_kses_post( $previous_link ); ?>
		<?php else : ?>
			<!-- wp:button {"disabled":true} -->
				<!-- wp:group {"tagName":"span", "className":"icon"} -->
				<span class="icon"></span>
				<!-- /wp:group -->
				<!-- wp:group {"tagName":"span"} -->
				<span><?php /* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/ */ esc_html_e( 'Previous', 'amnesty' ); ?></span>
				<!-- /wp:group -->
			<!-- /wp:button -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"tagName":"ul","className":"page-numbers","aria-label":"<?php /* translators: [front] AIRA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'Page numbers', 'amnesty' ); ?>"} -->
		<ul class="wp-block-group page-numbers" aria-label="<?php /* translators: [front] AIRA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'Page numbers', 'amnesty' ); ?>">
		<?php foreach ( $page_numbers as $number ) : ?>
			<!-- wp:group {"tagName":"li"} -->
			<li><?php echo wp_kses_post( $number ); ?></li>
			<!-- /wp:group -->
		<?php endforeach; ?>
		</ul>
		<!-- /wp:group -->
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationNext"} -->
		<div class="wp-block-group post-paginationLink post-paginationNext">
		<?php if ( $next_link ) : ?>
			<?php echo wp_kses_post( $next_link ); ?>
		<?php else : ?>
			<!-- wp:button {"disabled":true} -->
				<!-- wp:group {"tagName":"span", "className":"icon"} -->
				<span class="wp-block-group icon"></span>
				<!-- /wp:group -->
				<!-- wp:group {"tagName":"span"} -->
				<span><?php /* translators: [front]  https://isaidotorgstg.wpengine.com/en/latest/ */ esc_html_e( 'Next', 'amnesty' ); ?></span>
				<!-- /wp:group -->
			<!-- /wp:button -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</nav>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
