<?php

declare( strict_types = 1 );

add_filter(
	'wpseo_og_locale',
	function ( string $locale ): string {
		if ( ! is_multilingualpress_enabled() ) {
			return $locale;
		}

		return get_site_language_code();
	} 
);
