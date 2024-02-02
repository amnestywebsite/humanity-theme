<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_copyable_meta_keys' ) ) {
	/**
	 * Retrieve a list of valid meta keys for copying
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return array<int,string>
	 */
	function amnesty_copyable_meta_keys(): array {
		$keys = [
			'_disable_share_icons',
			'_disable_sidebar',
			'_display_author_info',
			'_hero_alignment',
			'_hero_background',
			'_hero_content',
			'_hero_cta_link',
			'_hero_cta_text',
			'_hero_embed',
			'_hero_show',
			'_hero_size',
			'_hero_title',
			'_hero_type',
			'_hero_video_id',
			'_nav_style',
			'_reduce_content_width',
			'_sidebar_id',
			'byline_context',
			'byline_entity',
			'byline_is_author',
			'document_ref',
			'download_id',
			'download_text',
			'term_slider',
			'hideSidebar', // annual reports
			'sidebarId', // annual reports
		];

		if ( defined( 'WPSEO_FILE' ) ) {
			$keys = array_merge(
				$keys,
				[
					'_yoast_wpseo_meta-robots-nofollow',
					'_yoast_wpseo_meta-robots-noindex',
					'_yoast_wpseo_metadesc',
					'_yoast_wpseo_opengraph-description',
					'_yoast_wpseo_opengraph-image-id',
					'_yoast_wpseo_opengraph-image',
					'_yoast_wpseo_opengraph-title',
					'_yoast_wpseo_title',
					'_yoast_wpseo_twitter-description',
					'_yoast_wpseo_twitter-image-id',
					'_yoast_wpseo_twitter-image',
					'_yoast_wpseo_twitter-title',
					'_yoast_wpseo_canonical',
				]
			);
		}

		return apply_filters( 'amnesty_copyable_meta_keys', $keys );
	}
}

if ( ! function_exists( 'amnesty_multilingualpress_sync_postmeta' ) ) {
	/**
	 * Copy source postmeta to translation postmeta if "Copy content" is selected in MLP
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param int $post_id the ID of the post that has been updated
	 *
	 * @return void
	 *
	 * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
	 */
	function amnesty_multilingualpress_sync_postmeta( int $post_id ) {
		// invalid nonce
		if ( ! isset( $_SERVER['HTTP_X_WP_NONCE'] ) || ! wp_verify_nonce( sanitize_key( $_SERVER['HTTP_X_WP_NONCE'] ), 'wp_rest' ) ) {
			return;
		}

		// no MLP data
		if ( ! array_key_exists( 'multilingualpress', $_POST ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$mlp = $_POST['multilingualpress'];

		$to_copy = [];
		$feature = [];

		foreach ( $mlp as $data ) {
			// post is being unlinked
			if ( ! isset( $data['relationship'] ) || 'remove' === $data['relationship'] ) {
				continue;
			}

			$copy_feature = isset( $data['remote-thumbnail-copy'] ) && 1 === absint( $data['remote-thumbnail-copy'] );
			$copy_content = isset( $data['remote-content-copy'] ) && 1 === absint( $data['remote-content-copy'] );

			// content isn't marked for copy to remote
			if ( ! $copy_feature && ! $copy_content ) {
				continue;
			}

			// no data
			if ( ! isset( $data['relation_context'] ) ) {
				continue;
			}

			$remote_site_id = absint( $data['relation_context']['remote_site_id'] );
			$remote_post_id = absint( $data['relation_context']['remote_post_id'] );

			// invalid data
			if ( 0 === $remote_site_id || 0 === $remote_post_id ) {
				continue;
			}

			if ( $copy_feature ) {
				$feature[] = compact( 'remote_site_id', 'remote_post_id' );
			}

			if ( $copy_content ) {
				$to_copy[] = compact( 'remote_site_id', 'remote_post_id' );
			}
		}

		// no posts are marked for copy
		if ( empty( $feature ) && empty( $to_copy ) ) {
			return;
		}

		// copy featured image to remote site(s)
		if ( ! empty( $feature ) ) {
			$source_feature = absint( get_post_meta( $post_id, '_thumbnail_id', true ) );

			// we only need to do this if the image is from the global media library
			if ( $source_feature && amnesty_image_has_mgm_prefix( $source_feature ) ) {
				foreach ( $feature as $remote ) {
					amnesty_copy_postmeta( $remote['remote_site_id'], $remote['remote_post_id'], [ '_thumbnail_id' => $source_feature ] );
				}
			}
		}

		$data_to_copy = [];

		// build data list to copy to remote site(s)
		foreach ( amnesty_copyable_meta_keys() as $key ) {
			$value = get_post_meta( $post_id, $key, true );

			if ( ! $value ) {
				continue;
			}

			$data_to_copy[ $key ] = $value;
		}

		// actually copy data list to remote site(s)
		foreach ( $to_copy as $remote ) {
			amnesty_copy_postmeta( $remote['remote_site_id'], $remote['remote_post_id'], $data_to_copy );
		}
	}
	// phpcs:enable Generic.Metrics.CyclomaticComplexity.TooHigh
}

add_action( 'post_updated', 'amnesty_multilingualpress_sync_postmeta' );
