<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Attachment\Copier;
use Inpsyde\MultilingualPress\TranslationUi\Post\RelationshipContext;

use function Inpsyde\MultilingualPress\resolve;

if ( ! function_exists( 'amnesty_mlp_block_media_attributes' ) ) {
	/**
	 * Retrieve the block attributes that store attachment IDs
	 *
	 * Nested attributes use dot notation, with * matching every item in a list.
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return array<string,array<int,string>>
	 */
	function amnesty_mlp_block_media_attributes(): array {
		$attributes = [
			'amnesty-core/action-block'            => [ 'imageID' ],
			'amnesty-core/background-media-column' => [ 'image', 'image.id' ],
			'amnesty-core/block-download'          => [ 'files.*', 'files.*.id' ],
			'amnesty-core/block-list'              => [ 'custom.*.featured_image_id' ],
			'amnesty-core/block-section'           => [ 'backgroundImageId' ],
			'amnesty-core/block-slider'            => [ 'slides.*.imageId' ],
			'amnesty-core/custom-card'             => [ 'imageID' ],
			'amnesty-core/header'                  => [ 'imageID', 'featuredVideoId' ],
			'amnesty-core/hero'                    => [ 'imageID', 'featuredImageId', 'featuredVideoId' ],
			'amnesty-core/image-block'             => [ 'imageID', 'videoID' ],
			'amnesty-core/links-with-icons'        => [ 'imageID' ],
		];

		return apply_filters( 'amnesty_mlp_block_media_attributes', $attributes );
	}
}

if ( ! function_exists( 'amnesty_mlp_copy_block_attachment' ) ) {
	/**
	 * Copy an attachment used in a block to the translation's site
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param int                 $attachment_id the attachment ID stored in the block
	 * @param RelationshipContext $context       the relationship context
	 * @param array<string,mixed> $state         the attachments copied so far
	 *
	 * @return int
	 */
	function amnesty_mlp_copy_block_attachment( int $attachment_id, RelationshipContext $context, array &$state ): int {
		if ( isset( $state['ids'][ $attachment_id ] ) ) {
			return $state['ids'][ $attachment_id ];
		}

		try {
			$remote_id = absint( resolve( Copier::class )->copyById( $context->sourceSiteId(), $context->remoteSiteId(), [ $attachment_id ] )[0] ?? 0 );
		} catch ( Throwable ) {
			$remote_id = 0;
		}

		if ( ! $remote_id ) {
			$state['ids'][ $attachment_id ] = $attachment_id;

			return $attachment_id;
		}

		$state['ids'][ $attachment_id ] = $remote_id;
		$state['changed']               = true;

		return $remote_id;
	}
}

if ( ! function_exists( 'amnesty_mlp_copy_attribute_media' ) ) {
	/**
	 * Copy the attachments referenced at an attribute path and update their IDs
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param mixed               $value    the attribute value
	 * @param array<int,string>   $segments the remaining attribute path
	 * @param RelationshipContext $context  the relationship context
	 * @param array<string,mixed> $state    the attachments copied so far
	 *
	 * @return mixed
	 */
	function amnesty_mlp_copy_attribute_media( mixed $value, array $segments, RelationshipContext $context, array &$state ): mixed {
		if ( $segments ) {
			$segment = array_shift( $segments );

			if ( ! is_array( $value ) ) {
				return $value;
			}

			foreach ( array_keys( $value ) as $key ) {
				if ( '*' === $segment || (string) $key === $segment ) {
					$value[ $key ] = amnesty_mlp_copy_attribute_media( $value[ $key ], $segments, $context, $state );
				}
			}

			return $value;
		}

		$attachment_id = is_bool( $value ) ? false : filter_var( $value, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] );

		if ( false === $attachment_id ) {
			return $value;
		}

		$remote_id = amnesty_mlp_copy_block_attachment( $attachment_id, $context, $state );

		return is_string( $value ) ? (string) $remote_id : $remote_id;
	}
}

if ( ! function_exists( 'amnesty_mlp_walk_media_blocks' ) ) {
	/**
	 * Apply a callback to every block that stores attachment IDs, including nested blocks
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param array<int,array>                $blocks           the parsed blocks
	 * @param array<string,array<int,string>> $media_attributes the attribute paths, keyed by block name
	 * @param callable                        $callback         receives the block and its attribute paths, returns the block
	 *
	 * @return array<int,array>
	 */
	function amnesty_mlp_walk_media_blocks( array $blocks, array $media_attributes, callable $callback ): array {
		foreach ( $blocks as $index => $block ) {
			if ( isset( $media_attributes[ $block['blockName'] ?? '' ] ) ) {
				$block = $callback( $block, $media_attributes[ $block['blockName'] ] );
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$block['innerBlocks'] = amnesty_mlp_walk_media_blocks( $block['innerBlocks'], $media_attributes, $callback );
			}

			$blocks[ $index ] = $block;
		}

		return $blocks;
	}
}

if ( ! function_exists( 'amnesty_mlp_copy_content_media' ) ) {
	/**
	 * Copy the media used in theme blocks when MLP copies a post's content
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param string              $content the source post content
	 * @param RelationshipContext $context the relationship context
	 *
	 * @return string
	 */
	function amnesty_mlp_copy_content_media( string $content, RelationshipContext $context ): string {
		if ( ! has_blocks( $content ) ) {
			return $content;
		}

		$media_attributes = amnesty_mlp_block_media_attributes();
		$state            = [
			'ids'     => [],
			'changed' => false,
		];

		$blocks = amnesty_mlp_walk_media_blocks(
			parse_blocks( $content ),
			$media_attributes,
			static function ( array $block, array $paths ) use ( $context, &$state ): array {
				foreach ( $paths as $path ) {
					$block['attrs'] = amnesty_mlp_copy_attribute_media( $block['attrs'], explode( '.', $path ), $context, $state );
				}

				return $block;
			}
		);

		if ( ! $state['changed'] ) {
			return $content;
		}

		return serialize_blocks( $blocks );
	}
}

add_filter( 'multilingualpress.content_before_update_remote_post', 'amnesty_mlp_copy_content_media', 20, 2 );
