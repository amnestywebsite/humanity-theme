<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Attachment\Copier;
use Inpsyde\MultilingualPress\TranslationUi\Post\RelationshipContext;
use MultisiteGlobalMedia\Site;

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

if ( ! function_exists( 'amnesty_mlp_attachment_urls' ) ) {
	/**
	 * Retrieve the URLs of an attachment's files on the current site, keyed by size
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param int $attachment_id the attachment ID on the current site
	 *
	 * @return array<string,string>
	 */
	function amnesty_mlp_attachment_urls( int $attachment_id ): array {
		$full = (string) wp_get_attachment_url( $attachment_id );
		$urls = [
			'full'     => $full,
			'original' => (string) wp_get_original_image_url( $attachment_id ),
		];

		foreach ( array_keys( (array) ( wp_get_attachment_metadata( $attachment_id )['sizes'] ?? [] ) ) as $size ) {
			$urls[ $size ] = (string) wp_get_attachment_image_url( $attachment_id, $size );
		}

		return array_filter( $urls );
	}
}

if ( ! function_exists( 'amnesty_mlp_attachment_url_map' ) ) {
	/**
	 * Map the upload paths of a source attachment's files to the URLs of its copy
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param array<string,string> $source_urls    the source URLs, from amnesty_mlp_attachment_urls()
	 * @param int                  $remote_site_id the site the attachment was copied to
	 * @param int                  $remote_id      the attachment ID on the remote site
	 *
	 * @return array<string,string>
	 */
	function amnesty_mlp_attachment_url_map( array $source_urls, int $remote_site_id, int $remote_id ): array {
		switch_to_blog( $remote_site_id );

		try {
			$remote_urls = amnesty_mlp_attachment_urls( $remote_id );
		} finally {
			restore_current_blog();
		}

		$map = [];

		foreach ( $source_urls as $size => $url ) {
			if ( preg_match( '~/uploads/(.+)$~', $url, $matches ) ) {
				$map[ $matches[1] ] = $remote_urls[ $size ] ?? $remote_urls['full'] ?? '';
			}
		}

		return array_filter( $map );
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
	 * @param array<string,mixed> $state         the attachments and URLs copied so far
	 *
	 * @return int
	 */
	function amnesty_mlp_copy_block_attachment( int $attachment_id, RelationshipContext $context, array &$state ): int {
		if ( isset( $state['ids'][ $attachment_id ] ) ) {
			return $state['ids'][ $attachment_id ];
		}

		$remote_site_id = $context->remoteSiteId();
		$source_id      = amnesty_get_image_without_mgm_prefix( $attachment_id );
		$source_site_id = $source_id !== $attachment_id ? ( new Site() )->id() : $context->sourceSiteId();

		if ( $source_site_id === $remote_site_id ) {
			$state['ids'][ $attachment_id ] = $source_id;
			$state['changed']               = $state['changed'] || $source_id !== $attachment_id;

			return $source_id;
		}

		switch_to_blog( $source_site_id );

		try {
			$source_urls = amnesty_mlp_attachment_urls( $source_id );
			$remote_id   = absint( resolve( Copier::class )->copyById( $source_site_id, $remote_site_id, [ $source_id ] )[0] ?? 0 );
		} catch ( Throwable ) {
			$remote_id = 0;
		} finally {
			restore_current_blog();
		}

		if ( ! $remote_id ) {
			$state['ids'][ $attachment_id ] = $attachment_id;

			return $attachment_id;
		}

		$state['ids'][ $attachment_id ] = $remote_id;
		$state['changed']               = true;
		$state['urls']                 += amnesty_mlp_attachment_url_map( $source_urls, $remote_site_id, $remote_id );

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
	 * @param array<string,mixed> $state    the attachments and URLs copied so far
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

if ( ! function_exists( 'amnesty_mlp_media_url_replacer' ) ) {
	/**
	 * Build a callback that points URLs of copied attachments at their copies
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param array<string,string> $urls the remote URLs, keyed by source file path
	 *
	 * @return callable
	 */
	function amnesty_mlp_media_url_replacer( array $urls ): callable {
		uksort( $urls, static fn ( string $a, string $b ): int => strlen( $b ) <=> strlen( $a ) );

		$files   = array_map( static fn ( string $file ): string => preg_quote( $file, '~' ), array_keys( $urls ) );
		$pattern = '~(?:(?:https?:)?//[^/\s"\'()<>&]+)?(?:/[^/\s"\'()<>&]+)*?/uploads/(' . implode( '|', $files ) . ')(?![\w.-])~';

		return static function ( &$value ) use ( $pattern, $urls ): void {
			if ( is_string( $value ) ) {
				$value = preg_replace_callback( $pattern, static fn ( array $matches ): string => $urls[ $matches[1] ], $value ) ?? $value;
			}
		};
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
			'urls'    => [],
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

		if ( $state['urls'] ) {
			$replace = amnesty_mlp_media_url_replacer( $state['urls'] );
			$blocks  = amnesty_mlp_walk_media_blocks(
				$blocks,
				$media_attributes,
				static function ( array $block ) use ( $replace ): array {
					array_walk_recursive( $block['attrs'], $replace );
					$replace( $block['innerHTML'] );
					array_walk( $block['innerContent'], $replace );

					return $block;
				}
			);
		}

		return serialize_blocks( $blocks );
	}
}

add_filter( 'multilingualpress.content_before_update_remote_post', 'amnesty_mlp_copy_content_media', 20, 2 );
