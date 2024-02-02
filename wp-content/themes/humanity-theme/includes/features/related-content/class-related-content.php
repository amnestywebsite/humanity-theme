<?php

declare( strict_types = 1 );

namespace Amnesty;

use WP_Post;
use WP_Query;
use WP_Term;

/**
 * Class for retrieving related content for a post
 *
 * @package Amnesty\Features
 */
class Related_Content {

	/**
	 * The object's country terms
	 *
	 * @var array<int,\WP_Term>
	 */
	protected array $countries = [];

	/**
	 * List of object terms, indexed by taxonomy
	 *
	 * @var array<string,array<int,\WP_Term>>
	 */
	protected array $terms = [];

	/**
	 * Standard arguments for WP_Query
	 *
	 * @var array<string,mixed>
	 */
	protected static array $base_query_args = [
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'post_type'           => 'post',
		'posts_per_page'      => 5,
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		'tax_query'           => [
			'relation' => 'OR',
			[
				'field'            => 'id',
				'include_children' => false,
				'operator'         => 'IN',
			],
		],
	];

	/**
	 * List of post IDs to retrieve
	 *
	 * @var array<int,int>
	 */
	protected array $post_ids = [];

	/**
	 * Quantity of posts to process
	 *
	 * @var int
	 */
	protected int $post_count = 5;

	/**
	 * The block attributes
	 *
	 * @var array<string,mixed>
	 */
	protected array $block_data = [];

	/**
	 * Immediately attempt to render the block.
	 *
	 * @param bool $output  whether to output on construct (default true)
	 * @param int  $post_id manual specification of post ID (overrides global $post)
	 */
	public function __construct( bool $output = true, int $post_id = 0 ) {
		$post_id = $post_id ?: get_the_ID();

		if ( $post_id ) {
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
			static::$base_query_args['post__not_in'] = [ $post_id ];
		}

		if ( ! $output ) {
			return;
		}

		$markup = $this->get_rendered();

		if ( ! $markup ) {
			return;
		}

		echo wp_kses_post( $markup );
	}

	/**
	 * Retrieve the rendered block markup
	 *
	 * @return string
	 */
	public function get_rendered(): string {
		$block_data = $this->get_block_data();
		$markup     = '';

		// don't render if there's nothing to render
		if ( ! $block_data['custom'] ) {
			return $markup;
		}

		$markup .= sprintf(
			'<h2 id="h-related-content" class="has-text-align-center">%s</h2>',
			/* translators: [front] shown on post single */
			esc_html__( 'Related Content', 'amnesty' )
		);

		$block_comment = sprintf(
			'<!-- wp:amnesty-core/block-list %s /-->',
			wp_json_encode( $block_data, JSON_UNESCAPED_UNICODE )
		);

		foreach ( parse_blocks( $block_comment ) as $parsed_block ) {
			$markup .= render_block( $parsed_block );
		}

		return $markup;
	}

	/**
	 * Retrieve the post data for the REST API
	 *
	 * @param array<string,array<int,array<string,mixed>>> $taxonomy_data the taxonomy data from the API
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function get_api_data( array $taxonomy_data ): array {
		// fetch data
		$this->get_api_object_terms( $taxonomy_data );
		$this->run_post_queries();
		// /fetch data

		if ( ! $this->post_ids ) {
			return [];
		}

		// build data
		// this taxonomy is handled differently
		$location_slug = amnesty_get_taxonomy_slug( 'location' );
		if ( isset( $this->terms[ $location_slug ][0] ) && is_a( $this->terms[ $location_slug ][0], WP_Term::class ) ) {
			$this->add_country_page_data( $this->terms[ $location_slug ][0] );
		}

		$this->add_posts_data();
		// /build data

		return $this->block_data['custom'];
	}

	/**
	 * Retrieve the terms declared as assigned to the post
	 *
	 * @param array<string,array<int,array<string,mixed>>> $taxonomy_data the taxonomy data from the API
	 *
	 * @return void
	 */
	protected function get_api_object_terms( array $taxonomy_data ): void {
		$taxonomies = get_taxonomies(
			[
				'amnesty' => true,
				'public'  => true,
			],
			'objects'
		);

		$taxonomies = apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! isset( $taxonomy_data[ $taxonomy->name ] ) ) {
				continue;
			}

			$terms = $taxonomy_data[ $taxonomy->name ];
			$terms = array_map( fn ( int $id ) => get_term( $id, $taxonomy->name ), $terms );
			$terms = array_filter( $terms, fn ( $term ): bool => is_a( $term, WP_Term::class ) );

			$this->terms[ $taxonomy->name ] = $terms;
		}

		$location_slug = amnesty_get_taxonomy_slug( 'location' );

		// we handle locations differently, as they have a "type"
		if ( ! isset( $this->terms[ $location_slug ] ) || ! count( $this->terms[ $location_slug ] ) ) {
			return;
		}

		foreach ( $this->terms[ $location_slug ] as $index => $location ) {
			$term = get_term( $location, $location_slug );

			if ( 'default' !== amnesty_get_location_type( $term ) || ! is_a( $term, WP_Term::class ) ) {
				unset( $this->terms[ $location_slug ][ $index ] );
				continue;
			}

			$this->terms[ $location_slug ][ $index ] = $term;
		}

		$this->terms[ $location_slug ] = array_values( $this->terms[ $location_slug ] );
	}

	/**
	 * Retrieve the block attributes
	 *
	 * @return array<string,mixed>
	 */
	protected function get_block_data(): array {
		// if it's already been processed, don't process it again
		if ( $this->block_data ) {
			return $this->block_data;
		}

		$this->block_data = [
			'style'  => 'grid',
			'type'   => 'custom',
			'custom' => [],
		];

		$cached_data = get_post_meta( get_the_ID(), 'related_posts_cache', true );

		// no timestamp, or it's older than a week, trash it
		if ( ! isset( $cached_data['timestamp'] ) || time() - $cached_data['timestamp'] > WEEK_IN_SECONDS ) {
			delete_post_meta( get_the_ID(), 'related_posts_cache' );

			$cached_data = null;
		}

		// cache is usable, so use it
		if ( isset( $cached_data['block_data'], $cached_data['block_data']['custom'] ) && $cached_data['block_data']['custom'] ) {
			$this->block_data = $cached_data['block_data'];

			return $this->block_data;
		}

		// fetch data
		$this->get_object_terms();
		$this->run_post_queries();
		// /fetch data

		// populated by run_post_queries
		if ( ! $this->post_ids ) {
			return $this->block_data;
		}

		// build data
		// this taxonomy is handled differently
		$location_slug = amnesty_get_taxonomy_slug( 'location' );
		if ( isset( $this->terms[ $location_slug ][0] ) && is_a( $this->terms[ $location_slug ][0], WP_Term::class ) ) {
			$this->add_country_page_data( $this->terms[ $location_slug ][0] );
		}

		$this->add_posts_data();
		// /build data

		// create cache
		if ( count( $this->block_data['custom'] ) ) {
			update_post_meta(
				get_the_ID(),
				'related_posts_cache',
				[
					'block_data' => $this->block_data,
					'timestamp'  => time(),
				]
			);
		}

		return $this->block_data;
	}

	/**
	 * Retrieve the terms assigned to this post
	 *
	 * @return void
	 */
	protected function get_object_terms(): void {
		$taxonomies = get_taxonomies(
			[
				'amnesty' => true,
				'public'  => true,
			],
			'objects'
		);

		$taxonomies = apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );

		foreach ( $taxonomies as $taxonomy ) {
			$this->terms[ $taxonomy->name ] = wp_get_object_terms( get_the_ID(), $taxonomy->name );
		}

		$location_slug = amnesty_get_taxonomy_slug( 'location' );

		// we handle locations differently, as they have a "type"
		if ( ! isset( $this->terms[ $location_slug ] ) || ! count( $this->terms[ $location_slug ] ) ) {
			return;
		}

		foreach ( $this->terms[ $location_slug ] as $index => $location ) {
			if ( 'default' !== amnesty_get_location_type( $location ) ) {
				unset( $this->terms[ $location_slug ][ $index ] );
			}
		}

		$this->terms[ $location_slug ] = array_values( $this->terms[ $location_slug ] );
	}

	/**
	 * Run the queries necessary to retrieve related posts
	 *
	 * @return void
	 */
	protected function run_post_queries(): void {
		if ( $this->post_ids ) {
			return;
		}

		$possible_posts = [];
		$previous_ids   = [];

		foreach ( array_keys( $this->terms ) as $taxonomy ) {
			$items = $this->run_taxonomy_query( $previous_ids, $taxonomy );

			if ( ! $items ) {
				continue;
			}

			$possible_posts = array_merge( $possible_posts, $items->posts );
			$previous_ids   = array_merge( $previous_ids, wp_list_pluck( $items->posts, 'ID' ) );
		}

		// sort by date descending
		usort(
			$possible_posts,
			function ( WP_Post $b, WP_Post $a ): int {
				return strtotime( $b->post_date_gmt ) <=> strtotime( $a->post_date_gmt );
			}
		);

		$this->post_ids = wp_list_pluck( array_slice( $possible_posts, 0, 5 ), 'ID' );
	}

	/**
	 * Retrieve posts in a taxonomy
	 *
	 * @param array<int,int> $post__not_in posts to exclude from results
	 * @param string         $taxonomy     the taxonomy slug
	 *
	 * @return \WP_Query|null
	 */
	protected function run_taxonomy_query( array $post__not_in = [], string $taxonomy = 'category' ): ?WP_Query {
		if ( ! count( $this->terms[ $taxonomy ] ) ) {
			return null;
		}

		$query_args = static::$base_query_args;

		// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
		$query_args['post__not_in'] = array_merge( $query_args['post__not_in'], $post__not_in );

		$query_args['tax_query'][0]['taxonomy'] = $taxonomy;
		$query_args['tax_query'][0]['terms']    = wp_list_pluck( $this->terms[ $taxonomy ], 'term_id' );

		$query = new WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return null;
		}

		return $query;
	}

	/**
	 * Retrieve the country data for use in the block
	 *
	 * @param \WP_Term $country the country term object
	 *
	 * @return void
	 */
	protected function add_country_page_data( WP_Term $country ): void {
		$image_id = absint( get_term_meta( $country->term_id, 'image_id', true ) );

		$this->block_data['custom'][] = [
			'id'                => $country->term_id,
			'type'              => 'term',
			'excerpt'           => '',
			'featured_image_id' => $image_id,
			'titleLink'         => amnesty_term_link( $country ),
			'title'             => $country->name,
			'tagText'           => __( 'Country', 'amnesty' ),
			'tagLink'           => amnesty_term_link( $country ),
		];

		// decrement the number of posts we need to fill the space with
		--$this->post_count;
	}

	/**
	 * Add posts data to block attributes
	 *
	 * @return void
	 */
	protected function add_posts_data(): void {
		if ( ! $this->post_ids ) {
			return;
		}

		$found_posts = new WP_Query(
			[
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'order'               => 'DESC',
				'orderby'             => 'post__in',
				'post__in'            => $this->post_ids,
				'post_status'         => 'publish',
				'post_type'           => 'post',
				'posts_per_page'      => $this->post_count,
			]
		);

		if ( ! $found_posts->have_posts() ) {
			return;
		}

		while ( $found_posts->have_posts() ) {
			$found_posts->the_post();

			$custom_item = [
				'id'                => get_the_ID(),
				'type'              => get_post_type(),
				'excerpt'           => get_the_excerpt(),
				'featured_image_id' => get_post_thumbnail_id(),
				'titleLink'         => get_permalink(),
				'title'             => get_the_title(),
			];

			$category = amnesty_get_prominent_term( get_the_ID() );

			if ( $category ) {
				$custom_item['tagText'] = $category->name;
				$custom_item['tagLink'] = amnesty_term_link( $category );
			}

			$this->block_data['custom'][] = $custom_item;
		}

		wp_reset_postdata();
	}

}
