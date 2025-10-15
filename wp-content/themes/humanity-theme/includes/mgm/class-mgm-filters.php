<?php

declare( strict_types = 1 );

namespace Amnesty;

use MultisiteGlobalMedia\Site;

new MGM_Filters();

/**
 * Filters for Multisite Global Media block transformations
 */
class MGM_Filters {

	/**
	 * MGM site helper object
	 *
	 * @var Site
	 */
	protected Site $site;

	/**
	 * Bind hooks
	 */
	public function __construct() {
		if ( class_exists( Site::class, false ) ) {
			$this->site = new Site();
		}

		add_filter( 'global_media.process_post.block_amnesty_core_block_section', [ $this, 'section' ], 10, 3 );
		add_filter( 'global_media.process_post.block_amnesty_core_hero', [ $this, 'hero' ], 10, 3 );
		add_filter( 'global_media.process_post.block_amnesty_core_block_slider', [ $this, 'slider' ], 10, 3 );
		add_filter( 'global_media.process_post.block_amnesty_core_action_block', [ $this, 'action' ], 10, 3 );
		add_filter( 'global_media.process_post.block_amnesty_core_background_media_column', [ $this, 'background_media_column' ], 10, 3 );
		add_filter( 'global_media.process_post.block_amnesty_core_custom_card', [ $this, 'custom_card' ], 10, 3 );
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function section( array $block ): array {
		$image_id = absint( $block['attrs']['backgroundImageId'] ?? '0' );

		if ( $image_id ) {
			$block['attrs']['backgroundImageId'] = $this->process( $image_id );
		}

		return $block;
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function hero( array $block ): array {
		$image_id = absint( $block['attrs']['imageID'] ?? '0' );

		if ( $image_id ) {
			$block['attrs']['imageID'] = $this->process( $image_id );
		}

		return $block;
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function slider( array $block ): array {
		foreach ( (array) $block['attrs']['slides'] as $index => $slide ) {
			$image_id = absint( $slide['imageId'] ?? '0' );

			if ( $image_id ) {
				$block['attrs']['slides'][ $index ]['imageId'] = $this->process( $image_id );
			}
		}

		return $block;
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function action( array $block ): array {
		$image_id = absint( $block['attrs']['imageID'] ?? '0' );

		if ( $image_id ) {
			$block['attrs']['imageID'] = $this->process( $image_id );
		}

		return $block;
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function background_media_column( array $block ): array {
		$image_id = absint( $block['attrs']['image'] ?? '0' );

		if ( $image_id ) {
			$block['attrs']['image'] = $this->process( $image_id );
		}

		return $block;
	}

	/**
	 * For when the origin is the global media site, add the prefix to the image ID
	 *
	 * @param array<string,mixed> $block the parsed block data
	 *
	 * @return array<string,mixed>
	 */
	public function custom_card( array $block ): array {
		$image_id = absint( $block['attrs']['imageID'] ?? '0' );

		if ( $image_id ) {
			$block['attrs']['imageID'] = $this->process( $image_id );
		}

		return $block;
	}

	/**
	 * Check if the given site Id prefix exists into the give attachment id
	 *
	 * @param int|string $id the attachment ID
	 *
	 * @return bool
	 */
	protected function media_has_prefix( int|string $id ): bool {
		return false !== strpos( (string) $id, $this->site->idSitePrefix() );
	}

	/**
	 * Process an attribute value
	 *
	 * @param int|string $id the attribute value
	 *
	 * @return int
	 */
	protected function process( int|string $id ): int {
		if ( $this->media_has_prefix( $id ) ) {
			return absint( $id );
		}

		return absint( $this->site->idSitePrefix() . $id );
	}

}
