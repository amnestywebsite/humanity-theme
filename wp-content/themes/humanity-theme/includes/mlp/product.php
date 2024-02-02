<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_mlp_pretty_permalinks' ) ) {
	/**
	 * Retrieve pretty permalinks for translations.
	 *
	 * Default MLP permalinks for CPTs are inconsistently pretty.
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param string $url     the permalink
	 * @param int    $blog_id the link's blog id
	 * @param int    $post_id the link's post id
	 *
	 * @return string
	 */
	function amnesty_mlp_pretty_permalinks( $url = '', $blog_id = 0, $post_id = 0 ) {
		return get_blog_permalink( $blog_id, $post_id );
	}
}

add_filter( 'multilingualpress.translation_url', 'amnesty_mlp_pretty_permalinks', 10, 3 );
