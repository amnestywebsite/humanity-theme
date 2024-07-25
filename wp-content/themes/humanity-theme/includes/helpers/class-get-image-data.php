<?php

declare( strict_types = 1 );

namespace Amnesty;

use MultisiteGlobalMedia\Attachment;
use MultisiteGlobalMedia\SingleSwitcher;
use MultisiteGlobalMedia\Site;
use ReflectionClass;

/**
 * Retrieve data for an image, potentially cross-site
 *
 * @package Amnesty
 */
class Get_Image_Data {

	/**
	 * The image's ID
	 *
	 * @var int
	 */
	protected int $image_id = 0;

	/**
	 * The image's URI
	 *
	 * @var string
	 */
	protected string $image_src = '';

	/**
	 * Setup member vars
	 *
	 * @param int    $image_id  the image's ID
	 * @param string $image_src the image's URI
	 */
	public function __construct( int $image_id, string $image_src = '' ) {
		$this->image_id  = $image_id;
		$this->image_src = $image_src;
	}

	/**
	 * Get the image ID
	 *
	 * @return int
	 */
	public function id(): int {
		return $this->image_id;
	}

	/**
	 * Get the image metadata HTML
	 *
	 * @param bool   $include_caption whether to output caption in metadata
	 * @param bool   $include_credit  whether to output caption in metadata
	 * @param string $type            the metadata type (enum: image,video)
	 *
	 * @return string
	 */
	public function metadata( bool $include_caption = true, bool $include_credit = true, string $type = 'image' ): string {
		$caption = $this->caption();
		$credit  = $this->credit();

		if ( ! $caption && ! $credit ) {
			return '';
		}

		// only support image/video; default to image
		if ( ! in_array( $type, [ 'image', 'video' ], true ) ) {
			$type = 'image';
		}

		$metadata = sprintf( '<div class="image-metadata is-%s">', esc_attr( $type ) );

		if ( $caption && $include_caption && trim( $caption ) !== trim( $credit ) ) {
			$metadata .= sprintf( '<span class="image-metadataItem image-caption" aria-hidden="true">%s</span>', $caption );
		}

		if ( $credit && $include_credit ) {
			$metadata .= sprintf( '<span class="image-metadataItem image-copyright" aria-hidden="true">%s</span>', $credit );
		}

		$metadata .= '</div>';

		return $metadata;
	}

	/**
	 * Retrieve the credit (description)
	 *
	 * @return string|null
	 */
	public function credit(): ?string {
		if ( 0 === $this->image_id ) {
			return '';
		}

		$image  = get_post( $this->image_id );
		$credit = null;

		if ( $image && 'attachment' === $image->post_type ) {
			$credit = wp_kses_post( $image->post_content );
		}

		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return $credit;
		}

		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $this->image_id ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return $cached;
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$prefix = $reflected->getMethod( 'idPrefixIncludedInAttachmentId' );
		$prefix->setAccessible( true );

		$strip = $reflected->getMethod( 'stripSiteIdPrefixFromAttachmentId' );
		$strip->setAccessible( true );

		// if the image ID doesn't include plugin's prefix
		if ( ! $prefix->invoke( $attachment, $this->image_id, $site_object->idSitePrefix() ) ) {
			$credit = $this->get_multisite_credit( $credit, $image );

			wp_cache_add( $cache_key, $credit );
			return $credit;
		}

		// get the non-prefixed image ID
		$source_image_id = $strip->invoke( $attachment, $site_object->idSitePrefix(), $this->image_id );

		// get its credit
		$switcher->switchToBlog( $site_object->id() );

		$image  = get_post( $source_image_id );
		$credit = null;

		if ( $image && 'attachment' === $image->post_type ) {
			$credit = wp_kses_post( $image->post_content );
		}

		$switcher->restoreBlog();

		wp_cache_add( $cache_key, $credit );

		return $credit;
	}

	/**
	 * Retrieve the alt text
	 *
	 * @return string
	 */
	public function alt_text(): string {
		$alt_text = get_post_meta( $this->image_id, '_wp_attachment_image_alt', true ) ?: '';

		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return $alt_text;
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$prefix = $reflected->getMethod( 'idPrefixIncludedInAttachmentId' );
		$prefix->setAccessible( true );

		$strip = $reflected->getMethod( 'stripSiteIdPrefixFromAttachmentId' );
		$strip->setAccessible( true );

		// if the image ID doesn't include plugin's prefix
		if ( ! $prefix->invoke( $attachment, $this->image_id, $site_object->idSitePrefix() ) ) {
			return $alt_text;
		}

		// get the non-prefixed image ID
		$source_image_id = $strip->invoke( $attachment, $site_object->idSitePrefix(), $this->image_id );

		// get its alt text
		$switcher->switchToBlog( $site_object->id() );
		$alt_text = get_post_meta( $source_image_id, '_wp_attachment_image_alt', true ) ?: '';
		$switcher->restoreBlog();

		return $alt_text;
	}

	/**
	 * Retrieve the caption (excerpt)
	 *
	 * @return string|null
	 */
	public function caption(): ?string {
		if ( 0 === $this->image_id ) {
			return '';
		}

		$image   = get_post( $this->image_id );
		$caption = null;

		if ( $image && 'attachment' === $image->post_type ) {
			$caption = wp_kses_post( $image->post_excerpt );
		}

		if ( ! class_exists( '\MultisiteGlobalMedia\Plugin', false ) ) {
			return $caption;
		}

		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $this->image_id ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return $cached;
		}

		$site_object = new Site();
		$switcher    = new SingleSwitcher();
		$attachment  = new Attachment( $site_object, $switcher );
		$reflected   = new ReflectionClass( $attachment );

		$prefix = $reflected->getMethod( 'idPrefixIncludedInAttachmentId' );
		$prefix->setAccessible( true );

		$strip = $reflected->getMethod( 'stripSiteIdPrefixFromAttachmentId' );
		$strip->setAccessible( true );

		// if the image ID doesn't include plugin's prefix
		if ( ! $prefix->invoke( $attachment, $this->image_id, $site_object->idSitePrefix() ) ) {
			$caption = $this->get_multisite_caption( $caption, $image );

			wp_cache_add( $cache_key, $caption );
			return $caption;
		}

		// get the non-prefixed image ID
		$source_image_id = $strip->invoke( $attachment, $site_object->idSitePrefix(), $this->image_id );

		// get its caption
		$switcher->switchToBlog( $site_object->id() );

		$image   = get_post( $source_image_id );
		$caption = null;

		if ( $image && 'attachment' === $image->post_type ) {
			$caption = wp_kses_post( $image->post_excerpt );
		}

		$switcher->restoreBlog();

		wp_cache_add( $cache_key, $caption );

		return $caption;
	}

	/**
	 * Check whether the image is on a remote site,
	 * and return credit from that site, if available
	 *
	 * @param string|null $caption the existing caption
	 * @param object|null $image   the image object
	 *
	 * @return string|null
	 */
	protected function get_multisite_credit( ?string $caption, ?object $image ): ?string {
		if ( $caption || $image || ! $this->image_src || ! defined( 'DOMAIN_CURRENT_SITE' ) ) {
			return $caption;
		}

		$domain   = wp_parse_url( $this->image_src, PHP_URL_HOST );
		$basepath = strstr( trim( wp_parse_url( $this->image_src, PHP_URL_PATH ), '/' ), '/', true );

		// get sites with a domain that matches the image URI's
		$sites = array_filter(
			amnesty_get_sites(),
			fn ( object $site ): bool =>
			false !== strpos( $site->url, "{$domain}/$basepath" )
		);

		// ambiguous result or no results
		if ( 1 !== count( $sites ) ) {
			return $caption;
		}

		$site_id = $sites[0]->site_id;

		switch_to_blog( $site_id );
		$image = get_post( $this->image_id );

		if ( $image ) {
			$caption = wp_kses_post( $image->post_content );
		}

		restore_current_blog();

		return $caption ?: null;
	}

	/**
	 * Check whether the image is on a remote site,
	 * and return caption from that site, if available
	 *
	 * @param string|null $caption the existing caption
	 * @param object|null $image   the image object
	 *
	 * @return string|null
	 */
	protected function get_multisite_caption( ?string $caption, ?object $image ): ?string {
		if ( $caption || $image || ! $this->image_src || ! defined( 'DOMAIN_CURRENT_SITE' ) ) {
			return $caption;
		}

		$domain   = wp_parse_url( $this->image_src, PHP_URL_HOST );
		$basepath = strstr( trim( wp_parse_url( $this->image_src, PHP_URL_PATH ), '/' ), '/', true );

		// get sites with a domain that matches the image URI's
		$sites = array_filter(
			amnesty_get_sites(),
			fn ( object $site ): bool =>
			false !== strpos( $site->url, "{$domain}/$basepath" )
		);

		// ambiguous result or no results
		if ( 1 !== count( $sites ) ) {
			return $caption;
		}

		$site_id = $sites[0]->site_id;

		switch_to_blog( $site_id );
		$image = get_post( $this->image_id );

		if ( $image ) {
			$caption = wp_kses_post( $image->post_excerpt );
		}

		restore_current_blog();

		return $caption ?: null;
	}

}
