<?php

declare( strict_types = 1 );

add_filter( 'big_bite_block_tabbed_content_show_tab_id_settings', '__return_true' );

if ( ! function_exists( 'register_block_type' ) ) {
	return;
}

require_once __DIR__ . '/_deprecated/header/class-header-block-renderer.php';
require_once __DIR__ . '/_deprecated/header/register.php';

require_once __DIR__ . '/action/register.php';
require_once __DIR__ . '/action/render.php';
require_once __DIR__ . '/background-media/components/register.php';
require_once __DIR__ . '/background-media/components/render.php';
require_once __DIR__ . '/background-media/register.php';
require_once __DIR__ . '/background-media/render.php';
require_once __DIR__ . '/banner/register.php';
require_once __DIR__ . '/blockquote/register.php';
require_once __DIR__ . '/blockquote/render.php';
require_once __DIR__ . '/call-to-action/register.php';
require_once __DIR__ . '/call-to-action/render.php';
require_once __DIR__ . '/collapsable/register.php';
require_once __DIR__ . '/collapsable/render.php';
require_once __DIR__ . '/countdown-timer/register.php';
require_once __DIR__ . '/countdown-timer/render.php';
require_once __DIR__ . '/custom-card/register.php';
require_once __DIR__ . '/custom-card/render.php';
require_once __DIR__ . '/download/register.php';
require_once __DIR__ . '/download/render.php';
require_once __DIR__ . '/embed-flourish/register.php';
require_once __DIR__ . '/embed-flourish/render.php';
require_once __DIR__ . '/embed-infogram/register.php';
require_once __DIR__ . '/embed-infogram/render.php';
require_once __DIR__ . '/embed-sutori/register.php';
require_once __DIR__ . '/embed-sutori/render.php';
require_once __DIR__ . '/embed-tickcounter/register.php';
require_once __DIR__ . '/embed-tickcounter/render.php';
require_once __DIR__ . '/hero/helpers.php';
require_once __DIR__ . '/hero/register.php';
require_once __DIR__ . '/hero/render.php';
require_once __DIR__ . '/iframe-button/register.php';
require_once __DIR__ . '/iframe-button/render.php';
require_once __DIR__ . '/iframe/register.php';
require_once __DIR__ . '/iframe/render.php';
require_once __DIR__ . '/image/register.php';
require_once __DIR__ . '/image/render.php';
require_once __DIR__ . '/link-group/register.php';
require_once __DIR__ . '/link-group/render.php';
require_once __DIR__ . '/links-with-icons/register.php';
require_once __DIR__ . '/links-with-icons/render.php';
require_once __DIR__ . '/menu/register.php';
require_once __DIR__ . '/menu/render.php';
require_once __DIR__ . '/petition-list/register.php';
require_once __DIR__ . '/petition-list/render.php';
require_once __DIR__ . '/post-list/register.php';
require_once __DIR__ . '/post-list/render.php';
require_once __DIR__ . '/raw-code/register.php';
require_once __DIR__ . '/regions/register.php';
require_once __DIR__ . '/regions/render.php';
require_once __DIR__ . '/related-content/register.php';
require_once __DIR__ . '/related-content/render.php';
require_once __DIR__ . '/section/class-section-block-renderer.php';
require_once __DIR__ . '/section/register.php';
require_once __DIR__ . '/slider/register.php';
require_once __DIR__ . '/slider/render.php';
require_once __DIR__ . '/stat-counter/register.php';
require_once __DIR__ . '/stat-counter/render.php';
require_once __DIR__ . '/term-list/register.php';
require_once __DIR__ . '/term-list/render.php';
require_once __DIR__ . '/tweet-action/register.php';
require_once __DIR__ . '/tweet-action/render.php';

if ( ! function_exists( 'amnesty_register_php_rendered_blocks' ) ) {
	/**
	 * Register the blocks that require php to be rendered.
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function amnesty_register_php_rendered_blocks() {
		register_action_block();
		register_background_media_block();
		register_background_media_column_block();
		register_banner_block();
		register_blockquote_block();
		register_collapsable_block();
		register_countdown_block();
		register_cta_block();
		register_custom_card_block();
		register_download_block();
		register_flourish_embed_block();
		register_header_block();
		register_hero_block();
		register_iframe_block();
		register_iframe_button_block();
		register_image_block();
		register_infogram_embed_block();
		register_link_group_block();
		register_links_with_icons_block();
		register_list_block();
		register_menu_block();
		register_petition_list_block();
		register_raw_code_block();
		register_regions_block();
		register_related_content_block();
		register_section_block();
		register_slider_block();
		register_stat_counter_block();
		register_sutori_embed_block();
		register_term_list_block();
		register_tickcounter_embed_block();
		register_tweet_action_block();
	}
}

add_action( 'init', 'amnesty_register_php_rendered_blocks' );

if ( ! function_exists( 'amnesty_filter_allowed_blocks' ) ) {
	/**
	 * Unregister unused or unwanted blocks
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return array
	 */
	function amnesty_filter_allowed_blocks(): array {
		$all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$namespaces = [ 'yoast', 'yoast-seo', 'woocommerce' ];
		$denylist   = [ 'core/cover' ];
		$hidden     = [
			'core/archives',
			'core/audio',
			'core/avatar',
			'core/calendar',
			'core/categories',
			'core/comments',
			'core/comments-pagination',
			'core/comment-content',
			'core/comment-edit-link',
			'core/comment-reply-link',
			'core/comment-template',
			'core/cover',
			'core/details',
			'core/file',
			'core/footnotes',
			'core/freeform',
			'core/gallery',
			'core/latest-comments',
			'core/loginout',
			'core/media-text',
			'core/more',
			'core/navigation',
			'core/nextpage',
			'core/page-list',
			'core/post-author',
			'core/post-author-biography',
			'core/post-author-name',
			'core/post-comments',
			'core/post-comments-form',
			'core/post-content',
			'core/post-date',
			'core/post-excerpt',
			'core/post-featured-image',
			'core/post-navigation-link',
			'core/post-template',
			'core/post-terms',
			'core/post-title',
			'core/preformatted',
			'core/query',
			'core/query-title',
			'core/read-more',
			'core/rss',
			'core/search',
			'core/site-logo',
			'core/site-tagline',
			'core/site-title',
			'core/tag-cloud',
			'core/term-description',
			'core/verse',
			'amnesty-core/image-block',
		];


		foreach ( $all_blocks as $name => $type ) {
			list( $vendor, $block ) = explode( '/', $name );

			// disallowed vendors
			if ( in_array( $vendor, $namespaces, true ) ) {
				unset( $all_blocks[ $name ] );
				continue;
			}

			// disallowed blocks
			if ( in_array( $name, $denylist, true ) ) {
				unset( $all_blocks[ $name ] );
				continue;
			}

			// blocks hidden from the inserter
			if ( in_array( $name, $hidden, true ) ) {
				$all_blocks[ $name ]->supports['inserter'] = false;
				continue;
			}
		}

		return array_keys( $all_blocks );
	}
}

add_filter( 'allowed_block_types_all', 'amnesty_filter_allowed_blocks', 100 );
