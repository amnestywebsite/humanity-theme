<?php

/**
 * Title: Search Results pattern
 * Description: Search results pattern for the theme
 * Slug: amnesty/search-results
 * Inserter: no
 */

$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';
$search_object = amnesty_get_searchpage_query_object();

// add filter to limit the post terms results for search
add_filter( 'get_the_terms', 'amnesty_limit_post_terms_results_for_search' );

$found_posts     = absint( $search_object->get_wp_query()->found_posts );
$found_posts_fmt = number_format_i18n( $found_posts );
$current_sort    = get_query_var( 'sort' ) ?: ( $GLOBALS['wp']->query_vars['sort'] ?? '' );
$available_sorts = amnesty_valid_sort_parameters();

/* translators: Singular/Plural number of posts. */
$results = sprintf( _n( '%s result', '%s results', $found_posts, 'amnesty' ), $found_posts_fmt );

if ( is_search() && get_search_query() ) {
	/* translators: 1: number of results for search query, 2: don't translate (dynamic search term) */
	$results = sprintf( _n( "%1\$s result for '%2\$s'", "%1\$s results for '%2\$s'", $found_posts, 'amnesty' ), $found_posts_fmt, get_search_query() );
}

$results = apply_filters( 'amnesty_search_results_title', $results, $found_posts, get_search_query() );

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

$has_term = function ( string $taxonomy = 'category' ): bool {
	switch_to_blog( get_post()->blog_id );
	$term = amnesty_get_a_post_term( get_the_ID(), $taxonomy );
	restore_current_blog();

	return (bool) $term;
};

$term_link = function ( string $taxonomy = 'category' ): string {
	switch_to_blog( get_post()->blog_id );
	$term = amnesty_get_a_post_term( get_the_ID(), $taxonomy );
	restore_current_blog();

	$switch = isset( get_post()->blog_id ) && absint( get_post()->blog_id ) !== get_current_blog_id();

	return $term ? amnesty_cross_blog_term_link( $term, $switch ) : '';
};

$term_name = function ( string $taxonomy = 'category' ): ?string {
	switch_to_blog( get_post()->blog_id );
	$term = amnesty_get_a_post_term( get_the_ID(), $taxonomy );
	restore_current_blog();

	return $term->name;
};

?>

<!-- wp:group {"tagName":"div"} -->
<div class="wp-block-group">
	<!-- wp:group {"tagName":"div","className":"section section--tinted search-results"} -->
	<div class="wp-block-group section section--tinted search-results">
		<!-- wp:group {"tagName":"header","className":"postlist-header"} -->
		<header class="wp-block-group postlist-header">
			<!-- wp:heading {"className":"postlist-headerTitle"} -->
			<h2 class="wp-block-heading postlist-headerTitle">
				<?php echo esc_html( $results ); ?>
			</h2>
			<!-- /wp:heading -->
			<?php

			// goes haywire in admin
			if ( ! is_admin() && ! ( defined( 'REST_REQUEST' ) && ! REST_REQUEST ) ) {
				$current_sort_option = $available_sorts[ $current_sort ] ?? null;

				// move current sort to the top of the list
				if ( $current_sort_option ) {
					unset( $available_sorts[ $current_sort ] );
					$available_sorts = [ $current_sort => $current_sort_option ] + $available_sorts;
				}

				amnesty_render_custom_select(
					[
						'label'      => __( 'Sort by', 'amnesty' ),
						'show_label' => true,
						'name'       => 'sort',
						'is_form'    => true,
						'multiple'   => false,
						'options'    => $available_sorts,
					]
				);
			}

			?>
		</header>
		<!-- /wp:group -->


	<?php if ( $search_object->get_wp_query()->have_posts() ) : ?>

		<!-- wp:list {"className":"wp-block-post-template is-layout-constrained"} -->
		<ul class="wp-block-list wp-block-post-template is-layout-constrained">

		<?php while ( $search_object->get_wp_query()->have_posts() ) : ?>
			<?php $search_object->get_wp_query()->the_post(); ?>

			<!-- wp:list-item {"className":"wp-block-post"} -->
			<li class="wp-block-list-item wp-block-post">
				<!-- wp:group {"tagName":"article","className":"post post--result"} -->
				<article class="wp-block-group post post--result">
					<!-- wp:group {"tagName":"div","className":"post-terms","layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group post-terms">

					<?php if ( $has_term() ) : ?>
						<!-- wp:group {"tagName":"div","className":"taxonomy-category post-category wp-block-post-terms"} -->
						<div class="wp-block-group taxonomy-category post-category wp-block-post-terms">
							<!-- wp:paragraph -->
							<p class="wp-block-paragraph">
								<a href="<?php echo esc_url( $term_link() ); ?>" rel="tag">
									<?php echo esc_html( $term_name() ); ?>
								</a>
							</p>
							<!-- /wp:paragraph -->
						</div>
						<!-- /wp:group -->
					<?php endif; ?>

					<?php if ( $has_term( $location_slug ) ) : ?>
						<!-- wp:group {"tagName":"div","className":"taxonomy-<?php echo esc_attr( $location_slug ); ?> post-<?php echo esc_attr( $location_slug ); ?> wp-block-post-terms"} -->
						<div class="wp-block-group taxonomy-<?php echo esc_attr( $location_slug ); ?> post-<?php echo esc_attr( $location_slug ); ?> wp-block-post-terms">
							<!-- wp:paragraph -->
							<p class="wp-block-paragraph">
								<a href="<?php echo esc_url( $term_link( $location_slug ) ); ?>" rel="tag">
									<?php echo esc_html( $term_name( $location_slug ) ); ?>
								</a>
							</p>
							<!-- /wp:paragraph -->
						</div>
						<!-- /wp:group -->
					<?php endif; ?>

					<?php if ( $has_term( 'topic' ) ) : ?>
						<!-- wp:group {"tagName":"div","className":"taxonomy-topic post-topic wp-block-post-terms"} -->
						<div class="wp-block-group taxonomy-topic post-topic wp-block-post-terms">
							<!-- wp:paragraph -->
							<p class="wp-block-paragraph">
								<a href="<?php echo esc_url( $term_link( 'topic' ) ); ?>" rel="tag">
									<?php echo esc_html( $term_name( 'topic' ) ); ?>
								</a>
							</p>
							<!-- /wp:paragraph -->
						</div>
						<!-- /wp:group -->
					<?php endif; ?>

					</div>
					<!-- /wp:group -->
					<!-- wp:heading {"level":2,"className":"post-title wp-block-post-title"} -->
					<h2 class="wp-block-heading post-title wp-block-post-title">
						<a href="<?php echo esc_url( get_blog_permalink( get_post()->blog_id, get_the_ID() ) ); ?>" target="_self"><?php the_title(); ?></a>
					</h2>
					<!-- /wp:heading -->
					<!-- wp:group {"tagName":"div","className":"post-excerpt wp-block-post-excerpt"} -->
					<div class="wp-block-group post-excerpt wp-block-post-excerpt">
						<!-- wp:paragraph {"className":"wp-block-post-excerpt__excerpt"} -->
						<p class="wp-block-paragraph wp-block-post-excerpt__excerpt"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group -->
					<div class="wp-block-group post-byline wp-block-post-date">
						<!-- wp:paragraph -->
						<p class="wp-block-paragraph">
							<time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
						</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</article>
				<!-- /wp:group -->
			</li>
			<!-- /wp:list-item -->

		<?php endwhile; ?>

		</ul>
		<!-- /wp:list -->

	<?php endif; ?>

	</div>
	<!-- /wp:group -->

<?php if ( $search_object->get_wp_query()->have_posts() ) : ?>

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

<?php endif; ?>

</div>
<!-- /wp:group -->

<?php wp_reset_postdata(); ?>
