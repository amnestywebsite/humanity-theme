<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_list_process_query' ) ) {
	/**
	 * Processes each post/page to return the the correct data format for our render function.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param WP_Query     $query Current WP_Query.
	 * @param object|false $term  a term to use, if supplied
	 * @param bool         $show_author whether to render author
	 * @param bool         $show_post_date whether to render post date
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_query( $query, $term = false, $show_author = false, $show_post_date = false ) {
		$posts = false;

		if ( $query->have_posts() ) {
			$posts = [];

			while ( $query->have_posts() ) {
				$query->the_post();

				$item = [
					'id'                => get_the_ID(),
					'showAuthor'        => $show_author,
					'showPostDate'      => $show_post_date,
					'author'            => get_the_author(),
					'date'              => get_the_date(),
					'title'             => get_the_title(),
					'link'              => get_the_permalink(),
					'tag'               => false,
					'tag_link'          => false,
					'featured_image'    => amnesty_featured_image( get_the_ID(), 'post-half@2x' ),
					'featured_image_id' => get_post_thumbnail_id( get_the_ID() ),
					'excerpt'           => get_the_excerpt(),
				];

				if ( ! $term ) {
					$term = amnesty_get_prominent_term( get_the_ID() );
				}

				// check the post has one of the terms from the category list, if it does render the first one as a label
				if ( has_category( $term, get_the_ID() ) ) {
					$terms            = get_the_category( get_the_ID() );
					$item['tag']      = $terms[0]->name;
					$item['tag_link'] = amnesty_term_link( $terms[0] );
				}

				$posts[] = $item;
			}

			wp_reset_postdata();
		}

		return $posts;
	}
}

if ( ! function_exists( 'amnesty_list_process_category' ) ) {
	/**
	 * Process the attributes for the current block for the category type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_category( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['category'] ) || ! $attributes['category'] ) {
			return false;
		}

		if ( empty( $attributes ) || ! isset( $attributes['amount'] ) || ! $attributes['amount'] ) {
			$amount = 3;
		}

		$category = json_decode( $attributes['category'] );

		$category__in = [];
		if ( is_object( $category ) ) {
			// deprecated variant
			$category__in = [ $category->value ];
		} elseif ( is_array( $category ) ) {
			$category__in = array_map(
				function ( $c ) {
					return $c->value;
				},
				$category
			);
		}

		if ( empty( $category__in ) ) {
			return [];
		}

		$post_categories = false;

		if ( ! empty( $attributes['categoryRelated'] ) && is_singular( 'post' ) ) {
			$post_categories = array_map(
				function ( $term ) {
					return $term->term_id;
				},
				wp_get_post_terms( get_queried_object_id(), 'category' )
			);
		}

		$category_override = false;

		if ( $post_categories ) {
			$category__in = $post_categories;
		} else {
			$category_override = $category__in;
		}

		$amount = isset( $amount ) ? $amount : $attributes['amount'];

		$show_author        = $attributes['displayAuthor'];
		$show_post_date     = $attributes['displayPostDate'];
		$override_post_type = $attributes['postTypes'] ?? (object) [
			'name' => 'post',
		];

		$query = new WP_Query(
			[
				'category__in'   => $category__in,
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
				'post__not_in'   => [ get_the_ID() ],
				'posts_per_page' => $amount,
				'no_found_rows'  => true,
				'post_type'      => $override_post_type->name,
			]
		);

		return amnesty_list_process_query( $query, $category_override, $show_author, $show_post_date );
	}
}

if ( ! function_exists( 'amnesty_list_process_author' ) ) {
	/**
	 * Process the attributes for the current block for the category type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_author( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['authors'] ) || ! $attributes['authors'] ) {
			return false;
		}

		if ( empty( $attributes ) || ! isset( $attributes['amount'] ) || ! $attributes['amount'] ) {
			$amount = 3;
		}

		$authors = json_decode( $attributes['authors'] );

		$author__in = [];
		if ( is_object( $authors ) ) {
			// deprecated variant
			$author__in = [ $authors->value ];
		} elseif ( is_array( $authors ) ) {
			$author__in = array_map(
				function ( $c ) {
					return $c->value;
				},
				$authors
			);
		}

		if ( empty( $author__in ) ) {
			return [];
		}

		$amount = isset( $amount ) ? $amount : $attributes['amount'];

		$query = new WP_Query(
			[
				'author__in'          => $author__in,
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
				'post__not_in'        => [ get_the_ID() ],
				'posts_per_page'      => $amount,
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			]
		);

		return amnesty_list_process_query( $query, false );
	}
}

if ( ! function_exists( 'amnesty_list_process_custom' ) ) {
	/**
	 * Process the attributes for the current block for the custom type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_custom( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['custom'] ) || ! $attributes['custom'] ) {
			return false;
		}

		$show_author = $attributes['displayAuthor'] ?? false;
		$show_date   = $attributes['displayPostDate'] ?? false;

		return array_map(
			fn ( array $data ): array => amnesty_list_process_custom_item_data( $data, $show_author, $show_date ),
			$attributes['custom']
		);
	}
}

if ( ! function_exists( 'amnesty_list_process_custom_item_data' ) ) {
	/**
	 * Process a custom item's attributes
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array<string,mixed> $data the item data
	 * @param bool                $show_author whether to render author info
	 * @param bool                $show_date   whether to render specified date
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_list_process_custom_item_data( array $data, bool $show_author, bool $show_date ): array {
		$image = wp_get_attachment_image_url( $data['featured_image_id'] ?? 0, 'post-half@2x' );
		$date  = amnesty_locale_date( strtotime( $data['date'] ?? '' ) );

		return [
			'title'             => $data['title'] ?? false,
			'link'              => $data['titleLink'] ?? false,
			'tag'               => $data['tagText'] ?? false,
			'tag_link'          => $data['tagLink'] ?? false,
			'featured_image'    => $image,
			'featured_image_id' => $data['featured_image_id'] ?? 0,
			'excerpt'           => $data['excerpt'] ?? false,
			'showPostDate'      => $show_date,
			'date'              => $date,
			'showAuthor'        => $show_author,
			'author'            => $data['authorName'] ?? '',
		];
	}
}

if ( ! function_exists( 'amnesty_list_process_select' ) ) {
	/**
	 * Process the attributes for the current block for the object selection type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_select( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['selectedPosts'] ) || ! $attributes['selectedPosts'] ) {
			return false;
		}

		$post_types = get_post_types(
			[
				'public' => true,
			]
		);

		$show_author    = $attributes['displayAuthor'];
		$show_post_date = $attributes['displayPostDate'];

		$query = new WP_Query(
			[
				'post__in'            => $attributes['selectedPosts'],
				'post_type'           => $post_types,
				'no_found_rows'       => true,
				'orderby'             => 'post__in',
				'ignore_sticky_posts' => true,
			]
		);

		return amnesty_list_process_query( $query, false, $show_author, $show_post_date );
	}
}

if ( ! function_exists( 'amnesty_list_process_taxonomy' ) ) {
	/**
	 * Process the attributes for the current block for the category type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_taxonomy( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['taxonomy'] ) || ! $attributes['taxonomy'] ) {
			return false;
		}

		if ( empty( $attributes ) || ! isset( $attributes['amount'] ) || ! $attributes['amount'] ) {
			$amount = 3;
		}

		if ( empty( $attributes['terms'] ) ) {
			return false;
		}

		$taxonomy       = $attributes['taxonomy']['value'];
		$terms          = array_column( $attributes['terms'] ?? [], 'value' );
		$amount         = isset( $amount ) ? $amount : $attributes['amount'];
		$show_author    = $attributes['displayAuthor'];
		$show_post_date = $attributes['displayPostDate'];

		$query = new WP_Query(
			[
				'type'           => 'post',
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
				'post__not_in'   => [ get_the_ID() ],
				'posts_per_page' => $amount,
				'no_found_rows'  => true,
				'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $taxonomy,
						'terms'    => $terms,
						'field'    => 'term_id',
					],
				],
			]
		);

		return amnesty_list_process_query( $query, false, $show_author, $show_post_date );
	}
}

if ( ! function_exists( 'amnesty_list_process_content' ) ) {
	/**
	 * Process the current attributes by calling the specific function dependant on block type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_list_process_content( $attributes ) {
		if ( empty( $attributes['type'] ) ) {
			return amnesty_list_process_category( $attributes );
		}

		switch ( $attributes['type'] ) {
			case 'custom':
				return amnesty_list_process_custom( $attributes );
			case 'category':
				return amnesty_list_process_category( $attributes );
			case 'select':
				return amnesty_list_process_select( $attributes );
			case 'taxonomy':
				return amnesty_list_process_taxonomy( $attributes );
			case 'author':
				return amnesty_list_process_author( $attributes );
			default:
				return amnesty_list_process_category( $attributes );
		}
	}
}

if ( ! function_exists( 'amnesty_render_list_item' ) ) {
	/**
	 * Render the current block item as a list item.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $data - Item data.
	 *
	 * @return void
	 */
	function amnesty_render_list_item( $data ) {
		$title          = isset( $data['title'] ) ? $data['title'] : '';
		$author         = isset( $data['author'] ) ? $data['author'] : '';
		$post_date      = isset( $data['date'] ) ? $data['date'] : '';
		$show_author    = isset( $data['showAuthor'] ) ? $data['showAuthor'] : '';
		$show_post_date = isset( $data['showPostDate'] ) ? $data['showPostDate'] : '';
		$post_updated   = isset( $data['id'] ) ? get_post_meta( $data['id'], 'amnesty_updated', true ) : '';

		if ( $show_post_date && $post_updated ) {
			$post_updated = wp_date( get_option( 'date_format' ), strtotime( $post_updated ), new DateTimeZone( 'UTC' ) );
		}

		require realpath( __DIR__ . '/views/list-item.php' );
	}
}

if ( ! function_exists( 'amnesty_render_grid_item' ) ) {
	/**
	 * Render the current block item as a grid item.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $data - Item data.
	 *
	 * @return void
	 */
	function amnesty_render_grid_item( $data ) {
		$title = isset( $data['title'] ) ? $data['title'] : '';

		require realpath( __DIR__ . '/views/grid-item.php' );
	}
}

if ( ! function_exists( 'amnesty_render_list_block' ) ) {
	/**
	 * Render the list item block.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current block attributes.
	 *
	 * @return string
	 */
	function amnesty_render_list_block( $attributes ) {
		// Prevent a bug in the admin panel where the editor
		// shows a different post if the list item is selected
		// using one of the selection methods.
		if ( is_admin() ) {
			return '';
		}

		if ( doing_filter( 'get_the_excerpt' ) ) {
			return false;
		}

		$data = amnesty_list_process_content( $attributes );

		if ( ! $data ) {
			return '';
		}

		ob_start();

		if ( isset( $attributes['style'] ) && 'grid' === $attributes['style'] ) {
			// Checks how many items in the array and outputs a different class based on value
			if ( in_array( count( $data ), [ 1, 2, 3, 5, 6, 7 ], true ) ) {
				printf( '<div class="grid grid-%s post-list">', esc_attr( count( $data ) ) );
				array_map( 'amnesty_render_grid_item', $data );
				print '</div>';
			} else {
				printf( '<div class="grid grid-many">' );
				array_map( 'amnesty_render_grid_item', $data );
				print '</div>';
			}
			return ob_get_clean();
		}

		print '<ul class="linkList">';
		array_map( 'amnesty_render_list_item', $data );
		print '</ul>';

		return ob_get_clean();
	}
}
