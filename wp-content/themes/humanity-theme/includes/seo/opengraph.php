<?php

declare( strict_types = 1 );

use Yoast\WP\SEO\Values\Open_Graph\Images;

if ( ! function_exists( 'amnesty_yoast_opengraph_mgm_image' ) ) {
	/**
	 * Add an opengraph image when available if the site is running Multisite Global Media
	 *
	 * @package Amnesty\Plugins\Yoast
	 *
	 * @param \Yoast\WP\SEO\Values\Open_Graph\Images $images the image container object
	 *
	 * @return null
	 */
	function amnesty_yoast_opengraph_mgm_image( Images $images ) {
		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return null;
		}

		if ( ! is_single() ) {
			return null;
		}

		$image_id = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-image-id', true );
		$image_id = $image_id ?: get_post_meta( get_the_ID(), '_thumbnail_id', true );
		$image_id = absint( $image_id );

		// no image to add
		if ( 0 === $image_id ) {
			return null;
		}

		// yoast should be able to find it on its own
		if ( false === amnesty_image_has_mgm_prefix( $image_id ) ) {
			return null;
		}

		$new_image_id = amnesty_get_image_without_mgm_prefix( $image_id );

		// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		switch_to_blog( apply_filters( 'global_media.site_id', 1 ) );
		$images->add_image_by_id( $new_image_id );
		restore_current_blog();

		return null;
	}
}

add_filter( 'wpseo_add_opengraph_images', 'amnesty_yoast_opengraph_mgm_image' );
