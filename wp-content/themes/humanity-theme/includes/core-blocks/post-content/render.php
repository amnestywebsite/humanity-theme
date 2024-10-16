<?php

declare( strict_types = 1 );

add_filter( 'block_type_metadata_settings', 'amnesty_overload_core_post_content_render' );

if ( ! function_exists( 'amnesty_overload_core_post_content_render' ) ) {
	/**
	 * Overrides the render method of core/post-content
	 *
	 * @param array<string,mixed> $settings the block settings
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_overload_core_post_content_render( array $settings ): array {
		if ( isset( $settings['name'] ) && 'core/post-content' === $settings['name'] ) {
			// apply our filters to the output of core's block renderer
			$settings['render_callback'] = static function () use ( $settings ) {
				$original_output = call_user_func_array( $settings['render_callback'], func_get_args() );
				return apply_filters( 'amnesty_the_content', $original_output );
			};
		}

		return $settings;
	}
}
