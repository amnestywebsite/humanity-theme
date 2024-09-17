<?php

declare( strict_types = 1 );

add_filter( 'block_type_metadata_settings', 'amnesty_override_core_query_pagination_previous_render' );

if ( ! function_exists( 'amnesty_override_core_query_pagination_previous_render' ) ) {
	/**
	 * Overrides the render method of core/query-pagination-previous
	 *
	 * @param array<string,mixed> $settings the block settings
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_override_core_query_pagination_previous_render( array $settings ): array {
		if ( 'core/query-pagination-previous' === $settings['name'] ) {
			$settings['render_callback'] = 'amnesty_render_block_core_query_pagination_previous';
		}

		return $settings;
	}
}

if ( ! function_exists( 'amnesty_get_previous_posts_link' ) ) {
	/**
	 * Retrieves the previous posts page link.
	 *
	 * @param string $label Optional. Previous page link text.
	 *
	 * @return string HTML-formatted previous page link.
	 */
	function amnesty_get_previous_posts_link( $label = null ): string {
		if ( is_single() ) {
			return '';
		}

		global $paged;

		if ( null === $label ) {
			$label = __( '&laquo; Previous Page' );
		}

		/**
		 * Filters the anchor tag attributes for the previous posts page link.
		 *
		 * @param string $attributes Attributes for the anchor tag.
		 */
		$attr = apply_filters( 'previous_posts_link_attributes', '' );

		if ( $paged > 1 ) {
			return sprintf(
				'<a href="%1$s" %2$s><span class="icon"></span>%3$s</a>',
				previous_posts( false ),
				$attr,
				preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
			);
		}

		return sprintf(
			'<button disabled><span %1$s><span class="icon"></span>%2$s</span></button>',
			$attr,
			preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label )
		);
	}
}

if ( ! function_exists( 'amnesty_render_block_core_query_pagination_previous' ) ) {
	/**
	 * Renders the `core/query-pagination-previous` block on the server.
	 *
	 * @since 5.8.0
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content    Block default content.
	 * @param WP_Block $block      Block instance.
	 *
	 * @return string Returns the previous posts link for the query.
	 *
	 * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
	 */
	function amnesty_render_block_core_query_pagination_previous( array $attributes, string $content, WP_Block $block ): string {
		$page_key            = isset( $block->context['queryId'] ) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
		$enhanced_pagination = isset( $block->context['enhancedPagination'] ) && $block->context['enhancedPagination'];
		$page                = absint( $_GET[ $page_key ] ?? 1 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- taken from core
		$wrapper_attributes  = get_block_wrapper_attributes();
		$show_label          = (bool) $block->context['showLabel'] ?? true;
		$label_text          = esc_html( $attributes['label'] ?? __( 'Previous', 'default' ) );
		$label               = $show_label ? $label_text : '';
		$pagination_arrow    = get_query_pagination_arrow( $block, false );

		if ( ! $label ) {
			$wrapper_attributes .= ' aria-label="' . $label_text . '"';
		}

		if ( $pagination_arrow ) {
			$label = $pagination_arrow . $label;
		}

		$content = '';

		// Check if the pagination is for Query that inherits the global context
		// and handle appropriately.
		$filter_link_attributes = static function () use ( $wrapper_attributes ) {
			return $wrapper_attributes;
		};

		add_filter( 'previous_posts_link_attributes', $filter_link_attributes );
		$content = amnesty_get_previous_posts_link( $label );
		remove_filter( 'previous_posts_link_attributes', $filter_link_attributes );

		if ( 1 !== $page ) {
			$content = sprintf(
				'<a href="%1$s" %2$s>%3$s</a>',
				esc_url( add_query_arg( $page_key, $page - 1 ) ),
				$wrapper_attributes,
				$label
			);
		}

		if ( $enhanced_pagination && isset( $content ) ) {
			$p = new WP_HTML_Tag_Processor( $content );
			$q = [
				'tag_name'   => 'a',
				'class_name' => 'wp-block-query-pagination-previous',
			];

			if ( $p->next_tag( $q ) ) {
				$p->set_attribute( 'data-wp-key', 'query-pagination-previous' );
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
