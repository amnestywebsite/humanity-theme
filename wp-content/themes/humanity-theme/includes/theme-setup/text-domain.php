<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_load_core_textdomain' ) ) {
	/**
	 * Load the theme text domain for all template strings within the content
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @return void
	 */
	function amnesty_load_core_textdomain() {
		load_theme_textdomain( 'amnesty', get_stylesheet_directory() . '/languages' );
	}
}

add_action( 'after_setup_theme', 'amnesty_load_core_textdomain' );

if ( ! function_exists( 'amnesty_mofile_domain_prefix' ) ) {
	/**
	 * Add textdomain prefix to mofile path when loading textdomains
	 *
	 * Fn {wp_set_script_translations} requires JSON files to be prefixed
	 * with the textdomain. Since the filename is not customisable when
	 * generating the JSON files using WP CLI, the POMO files need to
	 * include the textdomain as a prefix. Not doing this will cause
	 * the script translations to fail to load, when loading from the
	 * theme's languages directory.
	 * However, prefixing these files with the text domain then causes
	 * {load_theme_textdomain} to fail to find the MO files for loading
	 * via PHP.
	 *
	 * Since it's easier to filter PHP MO file loading than it is to
	 * rename the JSON files, this is the approach that we have gone for.
	 * It seems very silly that core expects the textdomain prefix in
	 * one place, whilst not expecting it in another place, but that
	 * is the trade-off when bundling one's own translation files in
	 * a theme/plugin, rather than in a core languages directory, but
	 * for plugins/themes that aren't released via wp.org, there's
	 * little alternative.
	 *
	 * @package Amnesty\ThemeSetup
	 *
	 * @param string $mofile the path to the mofile to load
	 * @param string $domain the textdomain being loaded
	 *
	 * @return string
	 */
	function amnesty_mofile_domain_prefix( string $mofile, string $domain ): string {
		if ( 'amnesty' !== $domain ) {
			return $mofile;
		}

		return trailingslashit( dirname( $mofile ) ) . 'amnesty-' . basename( $mofile );
	}
}

add_filter( 'load_textdomain_mofile', 'amnesty_mofile_domain_prefix', 10, 2 );

if ( ! function_exists( 'register_theme_as_translatable' ) ) {
	/**
	 * Register this theme as a translatable package
	 *
	 * @see https://github.com/amnestywebsite/humanity-translation-management
	 *
	 * @param array<int,array<string,string>> $packages existing packages
	 *
	 * @return array<int,array<string,string>>
	 */
	function register_theme_as_translatable( array $packages = [] ): array {
		$packages[] = [
			'id'     => 'humanity-theme',
			'label'  => __( 'Humanity Theme', 'amnesty' ),
			'path'   => get_template_directory(),
			'pot'    => get_template_directory() . '/languages/amnesty.pot',
			'domain' => 'amnesty',
		];

		return $packages;
	}
}

add_filter( 'register_translatable_package', 'register_theme_as_translatable' );
