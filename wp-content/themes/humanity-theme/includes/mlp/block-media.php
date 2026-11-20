<?php

declare( strict_types = 1 );

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
