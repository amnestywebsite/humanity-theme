<?php

declare( strict_types = 1 );

use Inpsyde\MultilingualPress\Attachment\Copier;
use Inpsyde\MultilingualPress\Framework\Admin\PersistentAdminNotices;
use Inpsyde\MultilingualPress\Framework\Http\Request;
use Inpsyde\MultilingualPress\TranslationUi\MetaboxFieldsHelper;
use Inpsyde\MultilingualPress\TranslationUi\Post\MetaboxFields;
use Inpsyde\MultilingualPress\TranslationUi\Post\RelationshipContext;
use MultisiteGlobalMedia\Site;

use function Inpsyde\MultilingualPress\resolve;

if ( ! function_exists( 'amnesty_mlp_copy_global_featured_image' ) ) {
	/**
	 * Copy a Global Media featured image to a translation
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param RelationshipContext $context      the relationship context
	 * @param int                 $global_image the prefixed Global Media image ID
	 * @param int                 $image_id     the image ID on the Global Media site
	 *
	 * @return void
	 */
	function amnesty_mlp_copy_global_featured_image( RelationshipContext $context, int $global_image, int $image_id ): void {
		$media_site_id  = ( new Site() )->id();
		$remote_site_id = $context->remoteSiteId();
		$remote_post_id = $context->remotePostId();
		$copied         = [];

		if ( $media_site_id === $remote_site_id ) {
			$copied = [ $image_id ];
		} else {
			switch_to_blog( $media_site_id );

			try {
				$copied = resolve( Copier::class )->copyById( $media_site_id, $remote_site_id, [ $image_id ] );
			} catch ( Throwable ) {
				$copied = [];
			} finally {
				restore_current_blog();
			}
		}

		$target_image = absint( $copied[0] ?? 0 ) ?: $global_image;

		switch_to_blog( $remote_site_id );

		update_post_meta( $remote_post_id, '_thumbnail_id', $target_image );

		restore_current_blog();
	}
}

if ( ! function_exists( 'amnesty_mlp_copy_featured_image' ) ) {
	/**
	 * Copy the source post's featured image to its translation
	 *
	 * @package Amnesty\Plugins\Multilingualpress
	 *
	 * @param RelationshipContext    $context   the relationship context
	 * @param Request                $request   the current request
	 * @param PersistentAdminNotices $notices   MLP admin notices
	 * @param string                 $operation the MLP relationship operation
	 *
	 * @return void
	 */
	function amnesty_mlp_copy_featured_image( RelationshipContext $context, Request $request, PersistentAdminNotices $notices, string $operation ): void {
		$fields    = new MetaboxFieldsHelper( $context->remoteSiteId() );
		$is_ticked = (bool) $fields->fieldRequestValue( $request, MetaboxFields::FIELD_COPY_FEATURED );
		$is_new    = MetaboxFields::FIELD_RELATION_NEW === $operation;

		if ( ! $is_new && ! $is_ticked ) {
			return;
		}

		switch_to_blog( $context->sourceSiteId() );
		$source_image = absint( get_post_thumbnail_id( $context->sourcePostId() ) );
		restore_current_blog();

		if ( ! $source_image ) {
			return;
		}

		$image_id = amnesty_get_image_without_mgm_prefix( $source_image );

		if ( $image_id !== $source_image ) {
			amnesty_mlp_copy_global_featured_image( $context, $source_image, $image_id );
		}
	}
}

add_action( 'multilingualpress.metabox_after_relate_posts', 'amnesty_mlp_copy_featured_image', 10, 4 );
