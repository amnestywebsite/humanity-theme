<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_a11y_kses' ) ) {
	/**
	 * Allow a11y-related attributes in kses.
	 *
	 * @param array $allowed allowed attributes list
	 *
	 * @package Amnesty\Kses
	 *
	 * @return array
	 */
	function amnesty_a11y_kses( array $allowed = [] ): array {
		$attributes = [
			'aria-activedescendant' => true,
			'aria-expanded'         => true,
			'aria-haspopup'         => true,
			'aria-label'            => true,
			'aria-labelledby'       => true,
			'role'                  => true,
			'tabindex'              => true,
		];

		$elements = [
			'a',
			'aside',
			'button',
			'div',
			'span',
			'ul',
			'li',
		];

		foreach ( $elements as $tag ) {
			if ( ! isset( $allowed[ $tag ] ) ) {
				$allowed[ $tag ] = [];
			}

			$allowed[ $tag ] = array_merge( $allowed[ $tag ], $attributes );
		}

		$allowed = apply_filters( 'amnesty_kses_allowed_html', $allowed );

		return $allowed;
	}
}

add_filter( 'wp_kses_allowed_html', 'amnesty_a11y_kses' );

add_filter(
	'amnesty_kses_allowed_html',
	function ( array $allowed ) {
		$allowed['div']['style'] = true;
		return $allowed;
	}
);

if ( ! function_exists( 'amnesty_add_a11y_labels_to_tables' ) ) {
	/**
	 * Add accessibility labels to tables
	 *
	 * @package Amnesty\A11y
	 *
	 * @param string $content the post content
	 *
	 * @return string
	 */
	function amnesty_add_a11y_labels_to_tables( string $content ): string {
		if ( false === strpos( $content, '<table' ) ) {
			return $content;
		}

		if ( false === apply_filters( 'amnesty_add_a11y_to_tables', true ) ) {
			return $content;
		}

		/**
		 * Convert any `<td></td>`s in a single-row thead into `<th></th>`s.
		 */
		$content = preg_replace_callback(
			'/(<thead[^>]*?>[^<]*?<tr[^>]*?>)(([^<]*?<td[^>]*?>(?!<\/td>).*?<\/td>[^<]*?)+?)([^<]*?<\/tr>[^<]*?<\/thead>)/',
			function ( $matches ) {
				$open  = $matches[1];
				$tds   = $matches[2];
				$close = $matches[4];

				$ths = str_replace( 'td', 'th', $tds );

				return $open . $ths . $close;
			},
			$content
		);

		$content = preg_replace( '/<table([^>]*?)>/', '<table $1 role="table">', $content );
		$content = preg_replace( '/<tr([^>]*?scope="row"[^>]*?)>/', '<tr $1 role="rowheader">', $content );

		$content = str_replace( '<thead>', '<thead role="rowgroup">', $content );
		$content = str_replace( '<tbody>', '<tbody role="rowgroup">', $content );
		$content = str_replace( '<tfoot>', '<tfoot role="rowgroup">', $content );
		$content = str_replace( '<tr>', '<tr role="row">', $content );
		$content = str_replace( '<th>', '<th role="columnheader">', $content );
		$content = str_replace( '<td>', '<td role="cell">', $content );

		return $content;
	}
}

add_filter( 'the_content', 'amnesty_add_a11y_labels_to_tables' );
