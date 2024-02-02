<?php

/**
 * Search partial, pagination
 *
 * @package Amnesty\Partials
 */

// fewer than one page of results, don't bother
if ( $query_object->get_count() <= get_option( 'posts_per_page' ) ) {
	return;
}

$current_num = absint( get_query_var( 'paged' ) ) ?: 1;
$num_pages   = absint( ceil( $query_object->get_count() / get_option( 'posts_per_page' ) ) );

/* translators: [front] https://www.amnesty.org/en/latest/ Previous post navigation label */
$previous_link = get_previous_posts_link( '<span class="icon"></span><span>' . __( 'Previous', 'amnesty' ) . '</span>' );
/* translators: [front] https://www.amnesty.org/en/latest/ Next post navigation label */
$next_link = get_next_posts_link( '<span class="icon"></span><span>' . __( 'Next', 'amnesty' ) . '</span>', $num_pages );

$page_numbers = amnesty_paginate_links(
	[
		'mid_size'  => 1,
		'prev_next' => false,
		'type'      => 'array',
		'total'     => $num_pages,
	] 
);

if ( empty( $page_numbers ) ) {
	return;
}

?>

<section class="section post-pagination">
	<nav class="post-paginationContainer" role="navigation" aria-label="<?php echo esc_attr( __( 'Pagination', 'amnesty' ) ); ?>">
		<div class="post-paginationLink post-paginationPrevious">
		<?php if ( $previous_link ) : ?>
			<?php echo wp_kses_post( $previous_link ); ?>
		<?php else : ?>
			<button disabled>
				<span class="icon"></span>
				<span><?php /* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/ */ esc_html_e( 'Previous', 'amnesty' ); ?></span>
			</button>
		<?php endif; ?>
		</div>
		<ul class="page-numbers" aria-label="<?php /* translators: [front] AIRA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'Page numbers', 'amnesty' ); ?>">
		<?php foreach ( $page_numbers as $number ) : ?>
			<li><?php echo wp_kses_post( $number ); ?></li>
		<?php endforeach; ?>
		</ul>
		<div class="post-paginationLink post-paginationNext">
		<?php if ( $next_link ) : ?>
			<?php echo wp_kses_post( $next_link ); ?>
		<?php else : ?>
			<button disabled>
				<span class="icon"></span>
				<span><?php /* translators: [front]  https://isaidotorgstg.wpengine.com/en/latest/ */ esc_html_e( 'Next', 'amnesty' ); ?></span>
			</button>
		<?php endif; ?>
		</div>
	</nav>
</section>
