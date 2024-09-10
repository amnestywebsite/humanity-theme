<?php

declare( strict_types = 1 );

add_filter( 'block_type_metadata_settings', 'amnesty_override_core_query_pagination_next_render' );

if ( ! function_exists( 'amnesty_override_core_query_pagination_next_render' ) ) {
	/**
	 * Overrides the render method of core/query-pagination-next
	 *
	 * @param array<string,mixed> $settings the block settings
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_override_core_query_pagination_next_render( array $settings ): array {
		if ( 'core/query-pagination-next' === $settings['name'] ) {
			$settings['render_callback'] = 'amnesty_render_block_core_query_pagination_next';
		}

		return $settings;
	}
}

if ( ! function_exists( 'amnesty_get_next_posts_link' ) ) {
	/**
	 * Retrieves the next posts page link.
	 *
	 * @global int      $paged
	 * @global WP_Query $wp_query WordPress Query object.
	 *
	 * @param string $label    Content for link text.
	 * @param int    $max_page Optional. Max pages. Default 0.
	 *
	 * @return string HTML-formatted next posts page link.
	 */
	function amnesty_get_next_posts_link( $label = null, $max_page = 0 ): string {
		if ( is_single() ) {
			return '';
		}

		global $paged, $wp_query;

		if ( ! $max_page ) {
			$max_page = $wp_query->max_num_pages;
		}

		if ( ! $paged ) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$paged = 1;
		}

		$next_page = (int) $paged + 1;

		if ( null === $label ) {
			$label = __( 'Next Page &raquo;', 'default' );
		}

		/**
		 * Filters the anchor tag attributes for the next posts page link.
		 *
		 * @since 2.7.0
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */
		$attr = apply_filters( 'next_posts_link_attributes', '' );

		if ( $next_page <= $max_page ) {
			return sprintf(
				'<a href="%1$s" %2$s>%3$s<span class="icon"></span></a>',
				next_posts( $max_page, false ),
				$attr,
				preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
			);
		}

		return sprintf(
			'<span %1$s>%2$s<span class="icon"></span></span>',
			$attr,
			preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
		);
	}
}

if ( ! function_exists( 'amnesty_render_block_core_query_pagination_next' ) ) {
	/**
	 * Renders the `core/query-pagination-next` block on the server.
	 *
	 * @since 5.8.0
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string Returns the next posts link for the query pagination.
	 *
	 * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
	 */
	function amnesty_render_block_core_query_pagination_next( $attributes, $content, $block ) {
		$page_key            = isset( $block->context['queryId'] ) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
		$enhanced_pagination = isset( $block->context['enhancedPagination'] ) && $block->context['enhancedPagination'];
		$page                = absint( $_GET[ $page_key ] ?? 1 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- taken from core
		$max_page            = isset( $block->context['query']['pages'] ) ? (int) $block->context['query']['pages'] : 0;
		$wrapper_attributes  = get_block_wrapper_attributes();
		$show_label          = isset( $block->context['showLabel'] ) ? (bool) $block->context['showLabel'] : true;
		$show_label          = (bool) $block->context['showLabel'] ?? true;
		$label_text          = esc_html( $attributes['label'] ?? __( 'Next', 'default' ) );
		$label               = $show_label ? $label_text : '';
		$pagination_arrow    = get_query_pagination_arrow( $block, true );

		if ( ! $label ) {
			$wrapper_attributes .= ' aria-label="' . $label_text . '"';
		}

		if ( $pagination_arrow ) {
			$label .= $pagination_arrow;
		}

		$content = '';

		// Check if the pagination is for Query that inherits the global context.
		if ( ( isset( $block->context['query']['inherit'] ) && $block->context['query']['inherit'] ) || is_main_query() ) {
			$filter_link_attributes = static function () use ( $wrapper_attributes ) {
				return $wrapper_attributes;
			};

			add_filter( 'next_posts_link_attributes', $filter_link_attributes );
			// Take into account if we have set a bigger `max page`
			// than what the query has.
			global $wp_query;
			if ( $max_page > $wp_query->max_num_pages ) {
				$max_page = $wp_query->max_num_pages;
			}
			$content = amnesty_get_next_posts_link( $label, $max_page );
			remove_filter( 'next_posts_link_attributes', $filter_link_attributes );
		} elseif ( ! $max_page || $max_page > $page ) {
			$custom_query           = new WP_Query( build_query_vars_from_query_block( $block, $page ) );
			$custom_query_max_pages = (int) $custom_query->max_num_pages;
			if ( $custom_query_max_pages && $custom_query_max_pages !== $page ) {
				$content = sprintf(
					'<a href="%1$s" %2$s>%3$s</a>',
					esc_url( add_query_arg( $page_key, $page + 1 ) ),
					$wrapper_attributes,
					$label
				);
			}
			wp_reset_postdata(); // Restore original Post Data.
		}

		if ( $enhanced_pagination && isset( $content ) ) {
			$p = new WP_HTML_Tag_Processor( $content );
			$q = [
				'tag_name'   => 'a',
				'class_name' => 'wp-block-query-pagination-next',
			];

			if ( $p->next_tag( $q ) ) {
				$p->set_attribute( 'data-wp-key', 'query-pagination-next' );
				$p->set_attribute( 'data-wp-on--click', 'core/query::actions.navigate' );
				$p->set_attribute( 'data-wp-on-async--mouseenter', 'core/query::actions.prefetch' );
				$p->set_attribute( 'data-wp-watch', 'core/query::callbacks.prefetch' );

				$content = $p->get_updated_html();
			}
		}

		return $content;
	}
	// phpcs:enable Generic.Metrics.CyclomaticComplexity.TooHigh
}
