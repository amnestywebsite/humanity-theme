<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_filter_kses' ) ) {
	/**
	 * Declare filter-specific KSES tags
	 *
	 * @param array  $tags    currently-allowed HMTL tags/attributes
	 * @param string $context the KSES context
	 *
	 * @package Amnesty\Kses
	 *
	 * @return array
	 */
	function amnesty_filter_kses( array $tags, string $context ): array {
		if ( 'filter' !== $context ) {
			return $tags;
		}

		$global_attributes = _wp_add_global_attributes(
			[
				'aria-selected' => true,
			] 
		);

		return array_merge_recursive(
			$tags,
			[
				'ul'     => $global_attributes,
				'li'     => $global_attributes,
				'span'   => $global_attributes,
				'input'  => array_merge(
					$global_attributes,
					[
						'type'     => true,
						'value'    => true,
						'selected' => true,
						'name'     => true,
						'checked'  => true,
					] 
				),
				'label'  => $global_attributes,
				'div'    => array_merge(
					$global_attributes,
					[
						'tabindex' => true,
					] 
				),
				'select' => array_merge( $global_attributes, [ 'name' => true ] ),
				'option' => array_merge(
					$global_attributes,
					[
						'value'    => true,
						'selected' => true,
					] 
				),
			] 
		);
	}
}

add_filter( 'wp_kses_allowed_html', 'amnesty_filter_kses', 10, 2 );
