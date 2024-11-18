<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_get_sidebar_id' ) ) {
	/**
	 * Get the correct sidbar ID for the current object
	 *
	 * @return int
	 */
	function amnesty_get_sidebar_id(): int {
		// post-level override
		$sidebar_id = absint( get_post_meta( get_the_ID(), '_sidebar_id', true ) );

		if ( $sidebar_id ) {
			return $sidebar_id;
		}

		// global default
		return match ( get_post_type() ) {
			'page'  => absint( amnesty_get_option( '_default_sidebar_page' )[0] ?? 0 ),
			default => absint( amnesty_get_option( '_default_sidebar' )[0] ?? 0 ),
		};
	}
}

if ( ! function_exists( 'amnesty_is_sidebar_available' ) ) {
	/**
	 * Check whether a sidebar is available to render for the current object
	 *
	 * @return bool
	 */
	function amnesty_is_sidebar_available(): bool {
		$sidebar = get_post( amnesty_get_sidebar_id() );

		return is_a( $sidebar, WP_Post::class ) &&
			'publish' === $sidebar->post_status &&
			'sidebar' === $sidebar->post_type;
	}
}

$content_maximised = amnesty_validate_boolish(
	get_post_meta( get_the_ID(), '_maximise_post_content', true ),
	false,
);

if ( $content_maximised ) {
	return;
}

$sidebar_disabled = amnesty_validate_boolish(
	get_post_meta( get_the_ID(), '_disable_sidebar', true ),
	false,
);

if ( $sidebar_disabled ) {
	if ( is_page() ) {
		return;
	}

	// empty element is intentional
	echo '<aside class="wp-block-group article-sidebar"></aside>';
	return;
}

if ( ! amnesty_is_sidebar_available() ) {
	return;
}

$sidebar = get_post( amnesty_get_sidebar_id() );

?>

<aside class="wp-block-group article-sidebar"><?php echo wp_kses_post( apply_filters( 'the_content', $sidebar->post_content ?? '' ) ); ?></aside>
