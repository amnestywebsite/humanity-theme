<?php

declare( strict_types = 1 );

use MultisiteGlobalMedia\Attachment;
use MultisiteGlobalMedia\SingleSwitcher;
use MultisiteGlobalMedia\Site;

if ( ! function_exists( 'retrieve_main_category' ) ) {
	/**
	 * Retrieve a category based on a priority system
	 *
	 * @package Amnesty
	 *
	 * @param array $featured_categories any priority categories
	 * @param array $all_categories      the full list of categories
	 *
	 * @return object
	 */
	function retrieve_main_category( array $featured_categories = [], array $all_categories = [] ) {
		return array_reduce(
			$all_categories,
			// phpcs:ignore Universal.FunctionDeclarations.NoLongClosures.ExceedsRecommended
			function ( $previous, $category ) use ( $featured_categories ) {
				if ( $previous ) {
					return $previous;
				}

				if ( in_array( $category->slug, $featured_categories, true ) ) {
					return $category;
				}

				return $previous;
			},
			false
		);
	}
}

if ( ! function_exists( 'amnesty_the_content' ) ) {
	/**
	 * Display the post content.
	 * Like `the_content`, but with $post as the first parameter
	 *
	 * @package Amnesty
	 *
	 * @param mixed  $post           Optional. The post ID/object for context
	 * @param string $more_link_text Optional. Content for when there is more text.
	 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default false.
	 */
	function amnesty_the_content( $post = null, $more_link_text = null, $strip_teaser = false ) {
		$post    = get_post( $post );
		$content = get_the_content( $more_link_text, $strip_teaser, $post );

		/**
		 * Filters the post content.
		 *
		 * @since 0.71
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		/**
		 * Filters the post content after all other filters have been applied
		 *
		 * @param string  $content content of the current post.
		 * @param WP_Post $post    the current post object.
		 */
		$content = apply_filters( 'amnesty_the_content', $content, $post );

		echo $content; // phpcs:ignore
	}
}

if ( ! function_exists( 'amnesty_image_has_mgm_prefix' ) ) {
	/**
	 * Check whether an image has a Multisite Global Media prefix
	 *
	 * @package Amnesty
	 *
	 * @param integer $image_id the image to check
	 *
	 * @return bool
	 */
	function amnesty_image_has_mgm_prefix( int $image_id ): bool {
		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return false;
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$prefix = $reflected->getMethod( 'idPrefixIncludedInAttachmentId' );
		$prefix->setAccessible( true );

		return $prefix->invoke( $attachment, $image_id, $site_object->idSitePrefix() );
	}
}

if ( ! function_exists( 'amnesty_get_image_without_mgm_prefix' ) ) {
	/**
	 * Strip Multisite Global Media prefix from image ID
	 *
	 * @package Amnesty
	 *
	 * @param int $image_id the full image ID
	 *
	 * @return int
	 */
	function amnesty_get_image_without_mgm_prefix( int $image_id ): int {
		if ( ! amnesty_image_has_mgm_prefix( $image_id ) ) {
			return $image_id;
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$prefix = $reflected->getMethod( 'stripSiteIdPrefixFromAttachmentId' );
		$prefix->setAccessible( true );

		return $prefix->invoke( $attachment, $site_object->idSitePrefix(), $image_id );
	}
}

if ( ! function_exists( 'amnesty_get_image_credit' ) ) {
	/**
	 * Retrieve the image credit (description field)
	 *
	 * @package Amnesty
	 *
	 * @param int    $image_id  the image ID
	 * @param string $image_src the image URI
	 *
	 * @return string|null
	 */
	function amnesty_get_image_credit( int $image_id, string $image_src = '' ): ?string {
		$image = new \Amnesty\Get_Image_Data( $image_id, $image_src );
		return $image->credit();
	}
}

if ( ! function_exists( 'amnesty_get_image_caption' ) ) {
	/**
	 * Retrieve the image caption (excerpt field)
	 *
	 * @package Amnesty
	 *
	 * @param int    $image_id  the image ID
	 * @param string $image_src the image URI
	 *
	 * @return string|null
	 */
	function amnesty_get_image_caption( int $image_id, string $image_src = '' ): ?string {
		$image = new \Amnesty\Get_Image_Data( $image_id, $image_src );
		return $image->caption();
	}
}

if ( ! function_exists( 'amnesty_get_image_alt_text' ) ) {
	/**
	 * Retrieve the alt text for an image
	 *
	 * @package Amnesty
	 *
	 * @param int $image_id the image ID
	 *
	 * @return string
	 */
	function amnesty_get_image_alt_text( int $image_id ): string {
		$image = new \Amnesty\Get_Image_Data( $image_id );
		return $image->alt_text();
	}
}

if ( ! function_exists( 'amnesty_get_attachment_url' ) ) {
	/**
	 * Retrieve the URL for an attachment
	 *
	 * @package Amnesty
	 *
	 * @param int|null $post_id the attachment ID
	 *
	 * @return string|false
	 */
	function amnesty_get_attachment_url( ?int $post_id = 0 ) {
		if ( ! $post_id ) {
			return false;
		}

		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return wp_get_attachment_url( $post_id );
		}

		if ( ! amnesty_image_has_mgm_prefix( $post_id ) ) {
			return wp_get_attachment_url( $post_id );
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$strip = $reflected->getMethod( 'stripSiteIdPrefixFromAttachmentId' );
		$strip->setAccessible( true );

		// get the non-prefixed attachment ID
		$source_post_id = $strip->invoke( $attachment, $site_object->idSitePrefix(), $post_id );

		// get its URL
		$switcher->switchToBlog( $site_object->id() );
		$url = wp_get_attachment_url( $source_post_id );
		$switcher->restoreBlog();

		return $url;
	}
}
