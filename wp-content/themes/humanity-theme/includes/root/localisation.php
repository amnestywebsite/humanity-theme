<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_body_class_text_direction' ) ) {
	/**
	 * Add text direction to body class.
	 *
	 * Class list is a string in the admin panel,
	 * but an array in the front-end.
	 *
	 * @package Amnesty\L10n
	 *
	 * @param string|array $classes existing body classes.
	 *
	 * @return string|array
	 */
	function amnesty_body_class_text_direction( $classes = null ) {
		if ( is_rtl() ) {
			return $classes;
		}

		if ( is_admin() ) {
			$classes .= ' ltr';
		} else {
			$classes[] = 'ltr';
		}

		return $classes;
	}
}

add_filter( 'body_class', 'amnesty_body_class_text_direction' );
add_filter( 'admin_body_class', 'amnesty_body_class_text_direction' );

if ( ! function_exists( 'amnesty_locale_datetime' ) ) {
	/**
	 * Convert an epoch timestamp to a locale-aware datetime format.
	 *
	 * @package Amnesty\L10n
	 *
	 * @param string $epoch the epoch timestamp to convert
	 *
	 * @return string
	 */
	function amnesty_locale_datetime( $epoch = '' ) {
		$date_fmt = get_option( 'date_format' );
		$time_fmt = get_option( 'time_format' );

		try {
			$datetime = new DateTime( "@{$epoch}" );
		} catch ( Exception $e ) {
			return '';
		}

		$datetime->setTimezone( wp_timezone() );
		return $datetime->format( $date_fmt . ' ' . $time_fmt );
	}
}

if ( ! function_exists( 'amnesty_locale_date' ) ) {
	/**
	 * Convert an epoch timestamp to a locale-aware date format.
	 *
	 * @package Amnesty\L10n
	 *
	 * @param string $epoch the epoch timestamp to convert
	 *
	 * @return string
	 */
	function amnesty_locale_date( $epoch = '' ) {
		$date_fmt = get_option( 'date_format' );

		try {
			$datetime = new DateTime( "@{$epoch}" );
		} catch ( Exception $e ) {
			return '';
		}

		$datetime->setTimezone( wp_timezone() );

		// month name is not included in format
		if ( false === strpos( $date_fmt, 'F' ) ) {
			return $datetime->format( $date_fmt );
		}

		global $l10n;

		static $formatter;

		if ( ! isset( $formatter ) ) {
			$formatter = new IntlDateFormatter(
				get_locale(),
				IntlDateFormatter::FULL,
				IntlDateFormatter::FULL,
				wp_timezone(),
				IntlDateFormatter::GREGORIAN,
				'LLLL' // full month name
			);
		}

		$latin_month  = $datetime->format( 'F' );
		$locale_month = ucwords( $formatter->format( $datetime ) );

		return str_replace( $latin_month, $locale_month, $datetime->format( $date_fmt ) );
	}
}
