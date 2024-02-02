<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_slider_kses' ) ) {
	/**
	 * Declare slider-specific KSES tags
	 *
	 * @package Amnesty\Kses
	 *
	 * @param array  $tags    currently-allowed HMTL tags/attributes
	 * @param string $context the KSES context
	 *
	 * @return array
	 */
	function amnesty_slider_kses( array $tags, string $context ): array {
		if ( 'slider' !== $context ) {
			return $tags;
		}

		return array_merge_recursive(
			$tags,
			[
				'style'   => [],
				'h1'      => _wp_add_global_attributes( [] ),
				'h2'      => _wp_add_global_attributes( [] ),
				'li'      => _wp_add_global_attributes( [] ),
				'p'       => _wp_add_global_attributes( [] ),
				'span'    => _wp_add_global_attributes( [] ),
				'nav'     => _wp_add_global_attributes( [] ),
				'section' => _wp_add_global_attributes( [] ),
				'input'   => _wp_add_global_attributes(
					[
						'type'     => true,
						'value'    => true,
						'selected' => true,
						'name'     => true,
						'checked'  => true,
					] 
				),
				'label'   => _wp_add_global_attributes( [] ),
				'div'     => _wp_add_global_attributes(
					[
						'tabindex' => true,
					] 
				),
				'select'  => _wp_add_global_attributes( [ 'name' => true ] ),
				'option'  => _wp_add_global_attributes(
					[
						'value'    => true,
						'selected' => true,
					] 
				),
				'button'  => _wp_add_global_attributes(
					[
						'class'            => true,
						'selected'         => true,
						'data-slide-index' => true,
						'key'              => true,
						'style'            => true,
					] 
				),
				'a'       => _wp_add_global_attributes(
					[
						'value'    => true,
						'selected' => true,
						'href'     => true,
					] 
				),
			] 
		);
	}
}

add_filter( 'wp_kses_allowed_html', 'amnesty_slider_kses', 10, 2 );
