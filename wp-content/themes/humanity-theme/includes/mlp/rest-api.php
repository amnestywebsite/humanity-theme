<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Framework\Api\ContentRelations;
use function Inpsyde\MultilingualPress\resolve;

if ( ! function_exists( 'amnesty_rest_field_get_callback_mlp_post_relationships' ) ) {
	/**
	 * Get callback to allow retrieval of MLP
	 * post relationships via the REST API
	 *
	 * @package Amnesty\Multilingualpress
	 *
	 * @param array $post the post data
	 *
	 * @return array|null
	 */
	function amnesty_rest_field_get_callback_mlp_post_relationships( array $post ): ?array {
		try {
			return resolve( ContentRelations::class )->relations( get_current_blog_id(), absint( $post['id'] ), 'post' );
		} catch ( \Exception $e ) {
			return null;
		}
	}
}

if ( ! function_exists( 'amnesty_rest_field_update_callback_mlp_post_relationships' ) ) {
	/**
	 * Update callback to allow modification of MLP
	 * post relationships via REST API
	 *
	 * @package Amnesty\Multilingualpress
	 *
	 * @param array   $value the value to set
	 * @param WP_Post $post  the post to update
	 *
	 * @return bool
	 */
	function amnesty_rest_field_update_callback_mlp_post_relationships( array $value, WP_Post $post ): bool {
		try {
			$api = resolve( ContentRelations::class );
			$old = $api->relations( get_current_blog_id(), absint( $post->ID ), 'post' );

			$api->deleteRelation( $old, 'post' );
			$api->createRelationship( $value, 'post' );

			update_post_meta( $post->ID, '_trash_the_other_posts', '1' );

			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}
}

if ( ! function_exists( 'amnesty_register_rest_field_mlp_post_relationships' ) ) {
	/**
	 * Register MLP post relationships field(s) with the REST API
	 *
	 * @return void
	 */
	function amnesty_register_rest_field_mlp_post_relationships(): void {
		$post_types = apply_filters( 'amnesty_register_mlp_relationships_for_post_types', [ 'post' ] );

		foreach ( $post_types as $post_type ) {
			register_rest_field(
				$post_type,
				'mlpRelationships',
				[
					'get_callback'    => 'amnesty_rest_field_get_callback_mlp_post_relationships',
					'update_callback' => 'amnesty_rest_field_update_callback_mlp_post_relationships',
				]
			);
		}
	}
}

add_action( 'rest_api_init', 'amnesty_register_rest_field_mlp_post_relationships' );

if ( ! function_exists( 'amnesty_rest_field_get_callback_mlp_term_relationships' ) ) {
	/**
	 * Get callback to allow retrieval of MLP
	 * term relationships via the REST API
	 *
	 * @package Amnesty\Multilingualpress
	 *
	 * @param array $term the term data
	 *
	 * @return array|null
	 */
	function amnesty_rest_field_get_callback_mlp_term_relationships( array $term ): ?array {
		try {
			return resolve( ContentRelations::class )->relations( get_current_blog_id(), absint( $term['id'] ), 'term' );
		} catch ( \Exception $e ) {
			return null;
		}
	}
}

if ( ! function_exists( 'amnesty_rest_field_update_callback_mlp_term_relationships' ) ) {
	/**
	 * Update callback to allow modification of MLP
	 * term relationships via the REST API
	 *
	 * @package Amnesty\Multilingualpress
	 *
	 * @param array   $value the value to set
	 * @param WP_Term $term the term to update
	 *
	 * @return bool
	 */
	function amnesty_rest_field_update_callback_mlp_term_relationships( array $value, WP_Term $term ): bool {
		try {
			$api = resolve( ContentRelations::class );
			$old = $api->relations( get_current_blog_id(), absint( $term->term_id ), 'term' );

			$api->deleteRelation( $old, 'term' );
			$api->createRelationship( $value, 'term' );

			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}
}

if ( ! function_exists( 'amnesty_register_rest_field_mlp_term_relationships' ) ) {
	/**
	 * Register MLP term relationships field(s) with the REST API
	 *
	 * @return void
	 */
	function amnesty_register_rest_field_mlp_term_relationships(): void {
		register_rest_field(
			get_option( 'amnesty_location_slug' ) ?: 'location',
			'mlpRelationships',
			[
				'get_callback'    => 'amnesty_rest_field_get_callback_mlp_term_relationships',
				'update_callback' => 'amnesty_rest_field_update_callback_mlp_term_relationships',
			]
		);
	}
}

add_action( 'rest_api_init', 'amnesty_register_rest_field_mlp_term_relationships' );
