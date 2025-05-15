<?php

/**
 * Title: Search pagination
 * Description: Pagination for search results
 * Slug: amnesty/search-pagination
 * Inserter: no
 */

$search_object = amnesty_get_searchpage_query_object();

/* translators: [front] https://www.amnesty.org/en/latest/ Previous post navigation label */
$previous_link = get_previous_posts_link( '<span class="icon"></span><span>' . __( 'Previous', 'amnesty' ) . '</span>' );
$next_link     = get_next_posts_link(
	/* translators: [front] https://www.amnesty.org/en/latest/ Next post navigation label */
	'<span class="icon"></span><span>' . __( 'Next', 'amnesty' ) . '</span>',
	$search_object->get_wp_query()->max_num_pages,
);

$page_numbers = amnesty_paginate_links(
	[
		'mid_size'  => 1,
		'prev_next' => false,
		'type'      => 'array',
		'total'     => $search_object->get_wp_query()->max_num_pages,
	]
);

?>

<!-- wp:group {"tagName":"section","className":"section section--small post-pagination"} -->
<section class="wp-block-group section section--small post-pagination">
	<!-- wp:group {"tagName":"nav","className":"post-paginationContainer","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<nav class="wp-block-group post-paginationContainer">
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationPrevious"} -->
		<div class="wp-block-group post-paginationLink post-paginationPrevious">
		<?php if ( $previous_link && ! is_admin() ) : ?>
			<?php echo wp_kses_post( $previous_link ); ?>
		<?php else : ?>
			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-pagination is-disabled"} -->
			<div class="wp-block-button is-style-pagination is-disabled"><a class="wp-block-button__link wp-element-button"><span class="icon"></span>
			<span><?php /* translators: [front] https://www.amnesty.org/en/latest/ */ esc_html_e( 'Previous', 'amnesty' ); ?></span></a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
		<!-- wp:list {"className":"page-numbers"} -->
		<ul class="wp-block-list page-numbers">
		<?php foreach ( $page_numbers as $number ) : ?>
			<!-- wp:list-item -->
			<li class="wp-block-list-item"><?php echo wp_kses_post( $number ); ?></li>
			<!-- /wp:list-item -->
		<?php endforeach; ?>
		</ul>
		<!-- /wp:list -->
		<!-- wp:group {"tagName":"div","className":"post-paginationLink post-paginationNext"} -->
		<div class="wp-block-group post-paginationLink post-paginationNext">
		<?php if ( $next_link && ! is_admin() ) : ?>
			<?php echo wp_kses_post( $next_link ); ?>
		<?php else : ?>
			<!-- wp:buttons -->
			<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-pagination is-disabled"} -->
			<div class="wp-block-button is-style-pagination is-disabled"><a class="wp-block-button__link wp-element-button"><span><?php /* translators: [front] https://www.amnesty.org/en/latest/ */ esc_html_e( 'Next', 'amnesty' ); ?></span>
			<span class="icon"></span></a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons -->
		<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</nav>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
