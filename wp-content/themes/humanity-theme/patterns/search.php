<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

$search_object = amnesty_get_searchpage_query_object();
$location_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

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
		<!-- wp:pattern {"slug":"amnesty/search-header"} /-->

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
	<!-- wp:pattern {"slug":"amnesty/search-pagination"} /-->
<?php endif; ?>

</div>
<!-- /wp:group -->

<?php wp_reset_postdata(); ?>
