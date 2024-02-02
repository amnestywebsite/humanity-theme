<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_remove_hero_meta' ) ) {
	/**
	 * Remove hero meta if the hero block is removed.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param integer $post_id Current post id.
	 * @param WP_Post $post    Current post object.
	 *
	 * @return void
	 */
	function amnesty_remove_hero_meta( $post_id, $post ) {
		$block_identifier = '<!-- wp:amnesty-core/block-hero';

		if ( strpos( $post->post_content, $block_identifier ) !== false ) {
			return;
		}

		$meta_to_remove = [
			'_hero_alignment',
			'_hero_background',
			'_hero_size',
			'_hero_show',
			'_hero_cta_link',
			'_hero_content',
			'_hero_cta_text',
			'_hero_title',
			'_hero_hide_image_caption',
			'_hero_hide_image_credit',
		];

		foreach ( $meta_to_remove as $meta_key ) {
			if ( get_post_meta( $post_id, $meta_key, true ) ) {
				delete_post_meta( $post_id, $meta_key );
			}
		}
	}
}

add_action( 'save_post', 'amnesty_remove_hero_meta', 100, 2 );
