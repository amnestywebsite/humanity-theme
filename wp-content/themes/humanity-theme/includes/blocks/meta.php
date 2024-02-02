<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_core_register_meta' ) ) {
	/**
	 * Register all meta required for gutenberg/REST API.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function amnesty_core_register_meta() {
		$args = [
			'show_in_rest'  => true,
			'single'        => true,
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		];

		$integer    = array_merge( $args, [ 'type' => 'integer' ] );
		$string     = array_merge( $args, [ 'type' => 'string' ] );
		$boolean    = array_merge( $args, [ 'type' => 'boolean' ] );
		$bool_true  = array_merge( $boolean, [ 'default' => true ] );
		$bool_false = array_merge( $boolean, [ 'default' => false ] );

		register_meta( 'post', '_hero_title', $args );
		register_meta( 'post', '_hero_content', $args );
		register_meta( 'post', '_hero_cta_text', $args );
		register_meta( 'post', '_hero_cta_link', $args );
		register_meta( 'post', '_hero_alignment', $args );
		register_meta( 'post', '_hero_background', $args );
		register_meta( 'post', '_hero_size', $args );
		register_meta( 'post', '_hero_show', $args );
		register_meta( 'post', '_hero_type', $args );
		register_meta( 'post', '_hero_embed', $args );
		register_meta( 'post', '_hero_video_id', $integer );
		register_meta( 'post', '_hero_hide_image_caption', $bool_true );
		register_meta( 'post', '_hero_hide_image_copyright', $bool_false );

		register_meta( 'post', '_nav_style', $args );
		register_meta( 'post', '_disable_share_icons', $boolean );
		register_meta( 'post', '_disable_sidebar', $boolean );
		register_meta( 'post', '_display_author_info', $boolean );
		register_meta( 'post', '_hide_featured_image', $boolean );
		register_meta( 'post', '_hide_featured_image_caption', $bool_true );
		register_meta( 'post', '_maximize_post_content', $boolean );
		register_meta( 'post', '_reduce_content_width', $boolean );
		register_meta( 'post', '_sidebar_id', $integer );
		register_meta( 'post', '_stretch_thumbnail', $boolean );
		register_meta( 'post', 'byline_context', $string );
		register_meta( 'post', 'byline_entity', $string );
		register_meta( 'post', 'byline_is_author', $boolean );
		register_meta( 'post', 'disable_related_content', $boolean );
		register_meta( 'post', 'download_id', $integer );
		register_meta( 'post', 'download_text', $string );
		register_meta( 'post', 'show_published_date', $bool_true );
		register_meta( 'post', 'show_updated_date', $bool_true );
		register_meta( 'post', 'term_slider', $string );
	}
}

add_action( 'init', 'amnesty_core_register_meta' );
