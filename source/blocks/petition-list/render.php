<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_petition_list_process_content' ) ) {
	/**
	 * Process the current attributes by calling the specific function dependant on block type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_petition_list_process_content( $attributes ) {
		if ( empty( $attributes['type'] ) ) {
			return amnesty_list_process_category( $attributes );
		}

		switch ( $attributes['type'] ) {
			case 'custom':
				return amnesty_list_process_custom( $attributes );
			case 'category':
				return amnesty_list_process_category( $attributes );
			case 'select':
				return amnesty_petition_list_process_select( $attributes );
			case 'feed':
				return amnesty_petition_list_process_feed( $attributes );
			case 'template':
				if ( empty( $attributes['query'] ) ) {
					return '';
				}
				return amnesty_petition_list_process_query( $attributes['query'], false );
			default:
				return amnesty_list_process_category( $attributes );
		}
	}
}

if ( ! function_exists( 'amnesty_render_petition_item' ) ) {
	/**
	 * Render the current block item as a grid item.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $data - Item data.
	 *
	 * @return void
	 */
	function amnesty_render_petition_item( $data ) {
		$title = isset( $data['title'] ) ? $data['title'] : '';

		/* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/petition/nigeria-end-impunity-for-police-brutality-end-sars/ */
		$button_text = __( 'Act Now', 'amnesty' );
		if ( ! empty( $data['has_signed'] ) ) {
			/* translators: [front] https://isaidotorgstg.wpengine.com/en/latest/petition/nigeria-end-impunity-for-police-brutality-end-sars/  used by sections, when a form rather than an Iframe is used */
			$button_text = _x( 'Signed!', 'User has signed this petition.', 'amnesty' );
		}

		$feature = wp_get_attachment_image_url( absint( $data['featured_image'] ?? 0 ), 'post-half@2x' );

		?>
		<article class="grid-item petition-item" aria-label="Article: <?php echo esc_attr( format_for_aria_label( $title ) ); ?>" tabindex="0">
			<figure>
				<img class="petition-itemImage aiic-ignore" src="<?php echo esc_url( $feature ); ?>" alt="">
				<?php if ( ! empty( $data['tag'] ) ) : ?>
				<span class="petition-itemImageCaption">
					<?php
					if ( ! empty( $data['tag_link'] ) ) {
						printf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['tag_link'] ), esc_html( $data['tag'] ) );
					} else {
						echo esc_attr( $data['tag'] );
					}
					?>
				</span>
				<?php endif; ?>
			</figure>

			<div class="petition-item-content">
				<div class="petition-itemExcerpt">
				<?php
				if ( ! empty( $data['excerpt'] ) ) {
					echo esc_attr( $data['excerpt'] );
				}
				?>
				</div>
				<?php if ( $title ) : ?>
				<h3 class="petition-itemTitle">
					<?php
					if ( ! empty( $data['link'] ) ) {
						printf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['link'] ), esc_html( wp_trim_words( $title, 10 ) ) );
					} else {
						printf( '<span>%s</span>', esc_html( wp_trim_words( $title, 10 ) ) );
					}
					?>
				</h3>
				<?php endif; ?>
				<a class="btn petition-itemCta" href="<?php echo esc_url( $data['link'] ); ?>"><?php echo esc_html( $button_text ); ?></a>
			</div>
		</article>

		<?php
	}
}

if ( ! function_exists( 'amnesty_petition_list_process_query' ) ) {
	/**
	 * Processes each post/page to return the the correct data format for our render function.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param WP_Query     $query Current WP_Query.
	 * @param object|false $term  a term to use, if supplied
	 *
	 * @return array
	 */
	function amnesty_petition_list_process_query( $query, $term = false ) {
		$posts = [];

		if ( ! $query->have_posts() ) {
			return $posts;
		}

		// phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
		$user_signed_petitions = sanitize_text_field( $_COOKIE['amnesty_petitions'] ?? '' );
		if ( $user_signed_petitions ) {
			$user_signed_petitions = array_map( 'absint', explode( ',', $user_signed_petitions ) );
		}

		while ( $query->have_posts() ) {
			$query->the_post();

			$item = [
				'id'             => get_the_ID(),
				'title'          => get_the_title(),
				'link'           => get_the_permalink(),
				'tag'            => false,
				'tag_link'       => false,
				'featured_image' => get_post_meta( get_the_ID(), '_thumbnail_id', true ),
				'excerpt'        => get_the_excerpt(),
				'has_signed'     => in_array( get_the_ID(), (array) $user_signed_petitions, true ),
			];

			$term = amnesty_get_a_post_term( get_the_ID(), 'topic' );

			if ( is_a( $term, 'WP_Term' ) ) {
				$item['tag']      = $term->name;
				$item['tag_link'] = amnesty_term_link( $term );
			}

			$posts[] = $item;
		}

		wp_reset_postdata();

		return $posts;
	}
}

if ( ! function_exists( 'amnesty_petition_list_process_feed' ) ) {
	/**
	 * Process the attributes for the current block for the object selection type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_petition_list_process_feed( $attributes ) {
		if ( empty( $attributes ) ) {
			return false;
		}

		$post_types = [ get_option( 'aip_petition_slug' ) ?: 'petition' ];

		$amount = 3; // the default
		if ( isset( $attributes['amount'] ) ) {
			$amount = absint( $attributes['amount'] );
		}

		$query = new WP_Query(
			[
				'post_type'      => $post_types,
				'no_found_rows'  => true,
				'posts_per_page' => $amount,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'tax_query'      => [
					[
						'taxonomy'         => 'visibility',
						'field'            => 'slug',
						'terms'            => 'hidden',
						'include_children' => false,
						'operator'         => 'NOT IN',
					],
				],
			]
		);

		return amnesty_petition_list_process_query( $query );
	}
}

if ( ! function_exists( 'amnesty_petition_list_process_select' ) ) {
	/**
	 * Process the attributes for the current block for the object selection type.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current Block attributes.
	 *
	 * @return array|bool
	 */
	function amnesty_petition_list_process_select( $attributes ) {
		if ( empty( $attributes ) || ! isset( $attributes['selectedPosts'] ) || ! $attributes['selectedPosts'] ) {
			return false;
		}

		$post_types = [ get_option( 'aip_petition_slug' ) ?: 'petition' ];

		$query = new WP_Query(
			[
				'post__in'      => $attributes['selectedPosts'],
				'post_type'     => $post_types,
				'no_found_rows' => true,
			]
		);

		return amnesty_petition_list_process_query( $query );
	}
}

if ( ! function_exists( 'amnesty_render_petition_list_block' ) ) {
	/**
	 * Render the list item block.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes - Current block attributes.
	 *
	 * @return string
	 */
	function amnesty_render_petition_list_block( $attributes ) {
		// Prevent a bug in the admin panel where the editor
		// shows a different post if the list item is selected
		// using one of the selection methods.
		if ( is_admin() ) {
			return '';
		}

		if ( doing_filter( 'get_the_excerpt' ) ) {
			return false;
		}

		$data = amnesty_petition_list_process_content( $attributes );

		if ( ! $data ) {
			return '';
		}

		ob_start();

		if ( isset( $attributes['style'] ) && 'grid' === $attributes['style'] ) {
			printf( '<div class="grid grid-%s">', esc_attr( count( $data ) ) );
			array_map( 'amnesty_render_grid_item', $data );
			print '</div>';

			return ob_get_clean();
		}

		if ( isset( $attributes['style'] ) && 'petition' === $attributes['style'] ) {
			// Checks how many items in the array and outputs a different class based on value
				$grid_classes = sprintf( 'grid grid-many' );
			if ( ! empty( $attributes['grid_class'] ) ) {
				$grid_classes = $attributes['grid_class'];
			}

			printf( '<div class="%s">', esc_attr( $grid_classes ) );
			array_map( 'amnesty_render_petition_item', $data );
			print '</div>';

			return ob_get_clean();
		}

		print '<ul class="linkList">';
		array_map( 'amnesty_render_list_item', $data );
		print '</ul>';

		return ob_get_clean();
	}
}
