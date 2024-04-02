<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Framework\Api\Translations;
use Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs;
use Inpsyde\MultilingualPress\Framework\WordpressContext;

use function Inpsyde\MultilingualPress\resolve;

if ( ! function_exists( 'is_multilingualpress_enabled' ) ) {
	/**
	 * Check whether MLP is enabled
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return boolean
	 */
	function is_multilingualpress_enabled() {
		return class_exists( '\\Inpsyde\\MultilingualPress\\MultilingualPress', false );
	}
}

if ( ! function_exists( 'get_string_textdirection' ) ) {
	/**
	 * Retrieve the directionality of a string
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param string $the_string the string to identify
	 *
	 * @return string
	 */
	function get_string_textdirection( string $the_string ): string {
		return 1 === preg_match( '/[\x{0590}-\x{083F}]|[\x{08A0}-\x{08FF}]|[\x{FB1D}-\x{FDFF}]|[\x{FE70}-\x{FEFF}]/u', $the_string ) ? 'rtl' : 'ltr';
	}
}


if ( ! function_exists( 'get_object_translations' ) ) {
	/**
	 * Retrieve translations for an object
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return array<int,\Inpsyde\MultilingualPress\Framework\Api\Translation>
	 */
	function get_object_translations() {
		if ( ! is_multilingualpress_enabled() ) {
			return [];
		}

		$cext = new WordPressContext();
		$site = get_current_blog_id();
		$args = TranslationSearchArgs::forContext( $cext )->forSiteId( $site )->includeBase();

		$cache_key = md5( wp_json_encode( $args->toArray() ) );
		$cached    = wp_cache_get( $cache_key );

		if ( $cached ) {
			return $cached;
		}

		$translations = resolve( Translations::class )->searchTranslations( $args );

		wp_cache_add( $cache_key, $translations );

		return $translations;
	}
}

if ( ! function_exists( 'has_translations' ) ) {
	/**
	 * Check whether object has translations
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @return boolean
	 */
	function has_translations() {
		return (bool) get_object_translations();
	}
}

if ( ! function_exists( 'list_object_translations' ) ) {
	/**
	 * List an object's translations
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param boolean $output whether to output or return
	 *
	 * @return void|string
	 */
	function list_object_translations( $output = false ) {
		$translations = get_object_translations();

		if ( ! $translations ) {
			return '';
		}

		$html = '<ul>';

		foreach ( $translations as $translation ) {
			$lang = $translation->language()->isoName();
			$type = $translation->type();

			// translators: [front] %1$s: the object type, %2$s: the object type's language
			$title = sprintf( __( 'View this %1$s in %2$s', 'amnesty' ), $type, $lang );

			$html .= sprintf(
				'<li><a href="%s" title="%s">%s</a></li>',
				esc_url( $translation->remoteUrl() ),
				esc_html( $title ),
				esc_html( $lang )
			);
		}

		$html .= '</ul>';

		if ( ! $output ) {
			return $html;
		}

		// phpcs:ignore
		echo $html;
	}
}
