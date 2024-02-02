<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_paginate_links' ) ) {
	/**
	 * Wrapper for paginate_links to add a11y labels.
	 *
	 * {@see paginate_links}
	 *
	 * @package Amnesty
	 *
	 * @param array $args the pagination arguments.
	 *
	 * @return array
	 */
	function amnesty_paginate_links( array $args = [] ) {
		$page_numbers = paginate_links( $args );

		$page_numbers = is_array( $page_numbers ) ? $page_numbers : [];

		foreach ( $page_numbers as $index => $html ) {
			// ignore "..." decorative span
			if ( false !== strpos( $html, '>&hellip;<' ) ) {
				$page_numbers[ $index ] = $html;
				continue;
			}

			// insert aria label for each page number.
			$page_numbers[ $index ] = preg_replace(
				'/^\s*?<(\w+)\s+([^>]+)>([^<]+)<\/\1>/',
				/* translators: [front] ARIA page number pagination prefix, e.g. "[Page] 1" https://isaidotorgstg.wpengine.com/en/latest/ */
				'<$1 aria-label="' . esc_attr__( 'Page', 'amnesty' ) . ' $3" $2>$3</$1>',
				$html
			);
		}

		return $page_numbers;
	}
}
