<?php

declare( strict_types = 1 );

if ( ! function_exists( 'render_sidebar_block' ) ) {
	/**
	 * Render the sidebar block
	 *
	 * @return string
	 */
	function render_sidebar_block(): string {
		$current = get_the_ID() ?: 0;

		$content_maximised = amnesty_validate_boolish(
			get_post_meta( $current, '_maximise_post_content', true ),
			false,
		);

		if ( $content_maximised ) {
			return '';
		}

		$sidebar_disabled = amnesty_validate_boolish(
			get_post_meta( $current, '_disable_sidebar', true ),
			false,
		);

		if ( $sidebar_disabled ) {
			// empty element is intentional
			return '<aside class="wp-block-group article-sidebar"></aside>';
		}

		$sidebar_id = absint( get_post_meta( $current, '_sidebar_id', true ) );
		if ( ! $sidebar_id ) {
			$sidebar_id = absint( amnesty_get_option( '_default_sidebar' )[0] ?? 0 );
		}

		if ( ! $sidebar_id ) {
			// empty element is intentional
			return '<aside class="wp-block-group article-sidebar"></aside>';
		}

		$sidebar = get_post( $sidebar_id );

		if ( ! $sidebar ) {
			// empty element is intentional
			return '<aside class="wp-block-group article-sidebar"></aside>';
		}

		return sprintf(
			'<aside class="wp-block-group article-sidebar">%s</aside>',
			apply_filters( 'the_content', $sidebar->post_content ),
		);
	}
}
