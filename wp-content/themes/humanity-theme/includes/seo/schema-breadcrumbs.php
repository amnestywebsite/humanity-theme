<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_yoast_fix_post_breadcrumb_schema' ) ) {
	/**
	 * Fix schema breadcrumbs from Yoast on post singles
	 *
	 * @package Amnesty\Plugins\Yoast
	 *
	 * @param array|null $piece the schema array
	 *
	 * @return array|null
	 */
	function amnesty_yoast_fix_post_breadcrumb_schema( ?array $piece ): ?array {
		if ( ! is_single() || ! is_array( $piece ) ) {
			return $piece;
		}

		// handle documents separately
		if ( 'attachment' === get_post_type() && 'application/pdf' === get_post_mime_type() ) {
			return $piece;
		}

		$home = trailingslashit( home_url( '/', 'https' ) );
		$link = get_permalink();

		// can't do anything about it
		if ( ! $link ) {
			return $piece;
		}

		$bits  = explode( '/', trim( str_replace( $home, '', $link ), '/' ) );
		$base  = $home;
		$items = [];

		foreach ( $bits as $bit ) {
			$base .= trailingslashit( $bit );

			$items[] = [
				'@type'    => 'ListItem',
				'position' => count( $items ) + 1,
				'item'     => [
					'@type' => 'WebPage',
					'@id'   => $base,
					'url'   => $base,
					'name'  => ucwords( str_replace( '-', ' ', $bit ) ),
				],
			];
		}

		$piece['itemListElement'] = $items;

		return $piece;
	}
}

// Fix breadcrumbs for single posts
add_filter( 'wpseo_schema_breadcrumb', 'amnesty_yoast_fix_post_breadcrumb_schema' );
