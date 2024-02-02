<?php

declare( strict_types = 1 );

if ( ! function_exists( 'get_archive_slider_posts' ) ) {
	/**
	 * Retrieve posts for use in a slider on an archive page
	 *
	 * @package Amnesty
	 *
	 * @param \WP_Term $term the term to query against
	 *
	 * @return array<int,\WP_Post>
	 */
	function get_archive_slider_posts( WP_Term $term ): array {
		$cache_key = md5( sprintf( '%s:%s:%s', __FUNCTION__, $term->slug, $term->taxonomy ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return $cached;
		}

		$slider_items = [];

		$raw_slider_items = new WP_Query(
			[
				'ignore_sticky_posts' => true, // perf: don't perform sticky logic
				'no_found_rows'       => true, // perf: no pagination required
				'post_type'           => 'post',
				'posts_per_page'      => 3,
				'meta_query'          => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'AND',
					[
						'key'     => 'term_slider',
						'value'   => $term->slug,
						'compare' => '=',
					],
					[
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					],
				],
			] 
		);

		if ( ! $raw_slider_items->have_posts() ) {
			wp_cache_set( $cache_key, [] );
			return $slider_items;
		}

		while ( $raw_slider_items->have_posts() ) {
			$raw_slider_items->the_post();

			$image_id   = get_post_thumbnail_id();
			$image_meta = wp_get_attachment_metadata( $image_id );

			if ( ! $image_meta || empty( $image_meta['sizes'] ) ) {
				continue;
			}

			$slider_items[] = [
				'id'               => get_the_ID(),
				'heading'          => get_the_title(),
				'callToActionLink' => get_permalink(),
				/* translators: [front] https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ */
				'callToActionText' => __( 'View now', 'amnesty' ),
				'imageId'          => $image_id,
				'imageUrl'         => get_the_post_thumbnail_url( get_the_ID(), 'hero-lg' ),
				'sizes'            => $image_meta['sizes'],
				'background'       => '',
				'title'            => '',
				'subheading'       => '',
				'alignment'        => '',
				'topics'           => wp_get_post_terms( get_the_ID(), 'topic' ),
			];
		}

		wp_reset_postdata();

		wp_cache_set( $cache_key, $slider_items );

		return $slider_items;
	}
}
