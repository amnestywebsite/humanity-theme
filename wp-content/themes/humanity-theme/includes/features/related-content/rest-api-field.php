<?php

declare( strict_types = 1 );

namespace Amnesty;

add_action( 'rest_api_init', '\Amnesty\register_related_content_field' );

/**
 * Build request args for a post to retrieve related content
 *
 * @return array<string,array<string,mixed>>
 */
function related_content_field_request_args(): array {
	$args = [];

	$taxonomies = get_taxonomies(
		[
			'amnesty' => true,
			'public'  => true,
		],
		'objects'
	);

	$taxonomies = apply_filters( 'amnesty_related_content_taxonomies', $taxonomies );

	foreach ( $taxonomies as $taxonomy ) {
		$args[ $taxonomy->name ] = [
			// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
			'description' => sprintf( __( 'The terms assigned to the post in the %s taxonomy.' ), $taxonomy->name ),
			'type'        => 'array',
			'items'       => [
				'type' => 'integer',
			],
			'context'     => [ 'view', 'edit' ],
		];
	}

	return $args;
}

/**
 * Retrieve related content for a post
 *
 * @param array<string,mixed> $post the post data
 *
 * @return array<int,int>
 */
function related_content_field_get_callback( array $post ): array {
	$related = new Related_Content( false, $post['id'] );

	$taxonomies = [];

	foreach ( array_keys( related_content_field_request_args() ) as $taxonomy ) {
		if ( ! isset( $post[ $taxonomy ] ) ) {
			continue;
		}

		$taxonomies[ $taxonomy ] = $post[ $taxonomy ];
	}

	return $related->get_api_data( $taxonomies );
}

/**
 * Adds related content as a property on post objects in the REST API
 *
 * @return void
 */
function register_related_content_field(): void {
	register_rest_field(
		'post',
		'relatedContent',
		[
			'get_callback'    => '\Amnesty\related_content_field_get_callback',
			'update_callback' => null,
		],
	);
}
