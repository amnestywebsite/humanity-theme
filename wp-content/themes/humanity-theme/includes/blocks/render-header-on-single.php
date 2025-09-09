<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_post_has_header' ) ) {
	/**
	 * Check whether post content contains a header block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param mixed $post The post to check
	 *
	 * @return bool
	 */
	function amnesty_post_has_header( mixed $post = null ): bool {
		$content = get_the_content( null, false, $post );

		return false !== strpos( $content, '<!-- wp:amnesty-core/block-hero' ) &&
			false === strpos( $content, '<!-- wp:amnesty-core/hero' );
	}
}

if ( ! function_exists( 'amnesty_find_header_block' ) ) {
	/**
	 * Retrieve header block from an array of parsed blocks
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $blocks The parsed blocks
	 *
	 * @return array
	 */
	function amnesty_find_header_block( array $blocks = [] ): array {
		return amnesty_find_first_block_of_type( $blocks, 'amnesty-core/block-hero' );
	}
}

if ( ! function_exists( 'amnesty_get_header_data' ) ) {
	/**
	 * Retrieve header block data for a post
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param mixed $post The post to get the data for
	 *
	 * @return array
	 */
	function amnesty_get_header_data( mixed $post = null ): array {
		if ( is_404() || is_search() ) {
			return [
				'name'    => '',
				'attrs'   => [],
				'content' => '',
			];
		}

		$post = get_post( $post );

		if ( ! isset( $post->ID ) || ! $post->ID ) {
			return [
				'name'    => '',
				'attrs'   => [],
				'content' => '',
			];
		}

		$blocks = parse_blocks( $post->post_content );
		$header = amnesty_find_header_block( $blocks );

		if ( ! count( $header ) ) {
			return [
				'name'    => '',
				'attrs'   => [],
				'content' => '',
			];
		}

		return [
			'name'    => $header['blockName'],
			'attrs'   => amnesty_get_header_data_from_meta( $post ),
			'content' => amnesty_render_blocks( $header['innerBlocks'] ),
		];
	}
}

if ( ! function_exists( 'amnesty_get_header_data_from_meta' ) ) {
	/**
	 * Retrieve header block data from postmeta
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param mixed $post The post to get the data for
	 *
	 * @return array
	 */
	function amnesty_get_header_data_from_meta( mixed $post = null ): array {
		global $wpdb;

		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $post->ID ) );
		$cached    = wp_cache_get( $cache_key );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$raw_data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT substring(meta_key, 7) as meta_key, meta_value
				FROM {$wpdb->postmeta}
				WHERE post_id = %d
				AND meta_key LIKE %s",
				$post->ID,
				'_hero_%'
			),
			ARRAY_A
		);

		if ( empty( $raw_data ) ) {
			return [];
		}

		$raw_data = array_column( $raw_data, 'meta_value', 'meta_key' );

		$data = [];

		foreach ( $raw_data as $key => $value ) {
			$data[ camel( $key ) ] = $value;
		}

		if ( isset( $data['videoId'] ) ) {
			$data['featuredVideoId'] = intval( $data['videoId'], 10 );
			unset( $data['videoId'] );
		}

		$data['imageID'] = intval( get_post_thumbnail_id( $post ), 10 );

		wp_cache_set( $cache_key, $data );

		return $data;
	}
}

if ( ! function_exists( 'amnesty_remove_header_from_content' ) ) {
	/**
	 * Strip the header block out of the content and overwrite it
	 * on the global object. This is normally a no-no, but in this
	 * specific case, it's nicer than messing up the template.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param string|null $content The content string, if applicable
	 *
	 * @return mixed
	 */
	function amnesty_remove_header_from_content( ?string $content = null ) {
		global $post;

		if ( ! is_a( $post, WP_Post::class ) ) {
			return;
		}

		$regex = '/<!--\s(wp:amnesty-core\/(?:block-hero))\s.*?(?:(?:\/-->)|(?:-->.*?<!--\s\/\1\s-->))/sm';

		if ( is_string( $content ) ) {
			return preg_replace( $regex, '', $content, 1 );
		}

		$post->post_content = preg_replace( $regex, '', $post->post_content, 1 );
	}
}
