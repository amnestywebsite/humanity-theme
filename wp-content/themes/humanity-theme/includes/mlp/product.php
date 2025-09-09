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
	 * @param string $url     The permalink
	 * @param int    $blog_id The link's blog id
	 * @param int    $post_id The link's post id
	 *
	 * @return string
	 */
	function amnesty_mlp_pretty_permalinks( string $url = '', int $blog_id = 0, int $post_id = 0 ) {
		return get_blog_permalink( $blog_id, $post_id );
	}
}

add_filter( 'multilingualpress.translation_url', 'amnesty_mlp_pretty_permalinks', 10, 3 );
