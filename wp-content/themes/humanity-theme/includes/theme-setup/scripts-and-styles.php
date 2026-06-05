<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_asset_uri' ) ) {
	/**
	 * Get asset/$folder dir URI.
	 *
	 * @package Amnesty\ThemeSetup
	 * @example echo asset_uri('images');
	 *
	 * @param string $folder Folder.
	 *
	 * @return string
	 */
	function amnesty_asset_uri( $folder ) {
		return get_template_directory_uri() . '/assets/' . $folder;
	}
}

if ( class_exists( '\Amnesty\Asset_Loader' ) ) {
	new \Amnesty\Asset_Loader();
}
