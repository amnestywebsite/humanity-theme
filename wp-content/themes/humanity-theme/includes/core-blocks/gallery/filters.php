<?php
if ( ! function_exists( 'remove_gallery_spacing_options' ) ) {
	/**
	 * Filter the metadata registration for the core/gallery block.
	 *
	 * @param array $metadata The block metadata.
	 *
	 * @return array
	 */
	function remove_gallery_spacing_options( $metadata ) {
		if ($metadata['name'] !== 'core/gallery' ) {
			return $metadata;
		}

		$metadata['supports']['spacing'] = false;

		return $metadata;
	};
}

add_filter('block_type_metadata', 'remove_gallery_spacing_options');
