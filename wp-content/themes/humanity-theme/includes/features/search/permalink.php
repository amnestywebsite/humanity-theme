<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_jsonld_search_url' ) ) {
	/**
	 * Tell yoast the pretty search URL
	 *
	 * @package Amnesty\Search
	 *
	 * @param string $original the original search URL
	 */
	function amnesty_jsonld_search_url( string $original = '' ): string {
		if ( false === apply_filters( 'amnesty_prettify_search_url', true ) ) {
			return $original;
		}

		return amnesty_search_url() . '{search_term_string}/';
	}
}

add_filter( 'wpseo_json_ld_search_url', 'amnesty_jsonld_search_url' );

if ( ! function_exists( 'amnesty_register_search_page_setting' ) ) {
	/**
	 * Register the settings definition for user-selectable search page
	 *
	 * @package Amnesty\Search
	 *
	 * @return void
	 */
	function amnesty_register_search_page_setting() {
		register_setting(
			'reading',
			'amnesty_search_page',
			[
				'show_in_rest' => true,
				'type'         => 'integer',
				'description'  => __( 'The ID of the page that should display the search interface', 'amnesty' ),
			]
		);

		add_settings_field(
			'amnesty_search_page',
			__( 'Search page', 'amnesty' ),
			// phpcs:ignore Universal.FunctionDeclarations.NoLongClosures.ExceedsMaximum
			function () {
				wp_dropdown_pages(
					[
						'name'              => 'amnesty_search_page',
						'echo'              => 1,
						'show_option_none'  => esc_html__( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => esc_attr( get_option( 'amnesty_search_page' ) ),
					]
				);
			},
			'reading',
			'default',
			[ 'label_for' => 'amnesty_search_page' ]
		);
	}
}

add_action( 'admin_init', 'amnesty_register_search_page_setting' );

if ( ! function_exists( 'amnesty_maybe_override_search_uri' ) ) {
	/**
	 * Replace old-style search URI generation with dynamic search URI
	 *
	 * For use with {@see home_url()}
	 *
	 * @package Amnesty
	 *
	 * @param string      $url  the generated URI
	 * @param string|null $path the requested path
	 *
	 * @return string
	 */
	function amnesty_maybe_override_search_uri( string $url, ?string $path = '/' ): string {
		if ( ! str_starts_with( strval( $path ), '/search/' ) ) {
			return $url;
		}

		remove_filter( 'home_url', 'amnesty_maybe_override_search_uri', 10, 2 );
		$url = amnesty_search_url();
		add_filter( 'home_url', 'amnesty_maybe_override_search_uri', 10, 2 );

		return $url;
	}
}

add_filter( 'home_url', 'amnesty_maybe_override_search_uri', 10, 2 );
