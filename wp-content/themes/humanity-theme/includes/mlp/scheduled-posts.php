<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\TranslationUi\Post\RelationshipContext;

if ( ! function_exists( 'amnesty_prevent_mlp_from_breaking_embargoes_on_translations' ) ) {
	/**
	 * Forcibly update a post on a remote site after MLP has duplicated it
	 *
	 * @see {amnesty_prevent_mlp_publishing_scheduled_posts()}
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @access private
	 *
	 * @param int    $blog_id the target site
	 * @param object $post    the target post object
	 *
	 * @return void
	 */
	function amnesty_prevent_mlp_from_breaking_embargoes_on_translations( int $blog_id, object $post ): void {
		switch_to_blog( $blog_id );

		// stop MLP breaking embargos by publishing posts that shouldn't be published
		wp_update_post(
			[
				'ID'                => $post->ID,
				'post_status'       => $post->post_status,
				'post_date'         => $post->post_date,
				'post_date_gmt'     => $post->post_date_gmt,
				'post_modified'     => $post->post_modified,
				'post_modified_gmt' => $post->post_modified_gmt,
			]
		);

		restore_current_blog();
	}
}

if ( ! function_exists( 'amnesty_prevent_mlp_publishing_scheduled_posts' ) ) {
	/**
	 * Prevents Multilingualpress from publishing scheduled relations of a post
	 * when said post is being saved.
	 *
	 * The default MLP behaviour is to sync the
	 * status/date of a post with its relations, making the assumption that
	 * their publishing schedule is synchronous. This doesn't work for Amnesty,
	 * as translations are rarely published synchronously.
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param RelationshipContext $context   The action context
	 * @param array<string,mixed> $post      The updated post data
	 * @param string              $operation The operation being performed
	 *
	 * @return void
	 */
	function amnesty_prevent_mlp_publishing_scheduled_posts( RelationshipContext $context, array $post, string $operation ): void {
		$source_post = $context->sourcePost();
		$remote_post = $context->remotePost();
		$remote_blog = $context->remoteSiteId();

		if ( 'leave' !== $operation ) {
			return;
		}

		if ( 'publish' === $source_post->post_status ) {
			return;
		}

		if ( 'publish' === $remote_post->post_status ) {
			return;
		}

		add_action_once(
			'multilingualpress.metabox_after_relate_posts',
			fn () => amnesty_prevent_mlp_from_breaking_embargoes_on_translations( $remote_blog, $remote_post ),
		);
	}
}

add_action( 'multilingualpress.metabox_before_update_remote_post', 'amnesty_prevent_mlp_publishing_scheduled_posts', 10, 3 );
