<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_strip_formatting_from_post_title' ) ) {
	/**
	 * Strip formatting from post title in post array
	 *
	 * @param array<string,mixed> $data the post array
	 *
	 * @return array<string,mixed>
	 */
	function amnesty_strip_formatting_from_post_title( array $data ): array {
		if ( isset( $data['post_title'] ) ) {
			$data['post_title'] = wp_strip_all_tags( $data['post_title'] );
		}

		return $data;
	}
}

add_filter( 'wp_insert_post_data', 'amnesty_strip_formatting_from_post_title' );
