<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Core\Admin\Settings\Cache\CacheSettingsOptions;

if ( ! function_exists( 'disable_caching_multilingualpress_api_data' ) ) {
	/**
	 * Prevent caching of some MultilingualPress queries
	 *
	 * Only prevent this caching in requests via wp-admin,
	 * so that frontend performance isn't impacted.
	 * This is to work around an issue where translations
	 * for posts are newly created, but the metabox doesn't
	 * reflect the new relationship(s).
	 *
	 * @param mixed $value the option value
	 *
	 * @return mixed
	 */
	function disable_caching_multilingualpress_api_data( mixed $value ): mixed {
		// settings defaults are in use - not retrieved from the database
		if ( ! is_array( $value ) || ! isset( $value[ CacheSettingsOptions::OPTION_GROUP_API_NAME ] ) ) {
			return $value;
		}

		// we don't want this impacting the frontend
		if ( ! is_admin() && ! is_ajax() && ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) ) {
			return $value;
		}

		// bypass caching of these data
		$value[ CacheSettingsOptions::OPTION_GROUP_API_NAME ][ CacheSettingsOptions::OPTION_RELATIONS_API_NAME ] = false;

		return $value;
	}
}

add_filter( 'site_option_multilingualpress_internal_cache_setting', 'disable_caching_multilingualpress_api_data' );
