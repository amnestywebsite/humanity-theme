<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_post_has_hero' ) ) {
	/**
	 * Check whether post content contains a hero block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param mixed $post the post to check
	 *
	 * @return bool
	 */
	function amnesty_post_has_hero( $post = null ): bool {
		$content = get_the_content( null, false, $post );
		return false !== strpos( $content, '<!-- wp:amnesty-core/hero' );
	}
}

if ( ! function_exists( 'amnesty_get_hero_data' ) ) {
	/**
	 * Retrieve hero block data for a post
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param mixed $post the post to get the data for
	 *
	 * @return array
	 */
	function amnesty_get_hero_data( $post = null ) {
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
		$hero   = amnesty_find_first_block_of_type( $blocks, 'amnesty-core/hero' );

		if ( ! count( $hero ) ) {
			return [
				'name'    => '',
				'attrs'   => [],
				'content' => '',
			];
		}

		return [
			'name'    => $hero['blockName'],
			'attrs'   => $hero['attrs'],
			'content' => amnesty_render_blocks( $hero['innerBlocks'] ),
		];
	}
}

if ( ! function_exists( 'amnesty_remove_first_hero_from_content' ) ) {
	/**
	 * Strip the hero block out of the content and overwrite it
	 * on the global object. This is normally a no-no, but in this
	 * specific case, it's nicer than messing up the template.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param string $content the post content
	 *
	 * @return string
	 */
	function amnesty_remove_first_hero_from_content( string $content ): string {
		return preg_replace(
			'/<!--\s(wp:amnesty-core\/(?:hero))\s.*?(?:(?:\/-->)|(?:-->.*?<!--\s\/\1\s-->))\s*?/sm',
			'',
			$content,
			1
		);
	}
}
