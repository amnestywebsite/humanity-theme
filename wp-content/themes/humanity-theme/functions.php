<?php

/**
 * Functions file
 *
 * @package Amnesty
 */

// phpcs:disable Squiz.Commenting.InlineComment.WrongStyle,PEAR.Commenting.InlineComment.WrongStyle

/**
 * Theme root includes
 */
#region root
require_once realpath( __DIR__ . '/includes/root/compatibility.php' );
require_once realpath( __DIR__ . '/includes/root/caching.php' );
require_once realpath( __DIR__ . '/includes/root/localisation.php' );
require_once realpath( __DIR__ . '/includes/root/accessibility.php' );
require_once realpath( __DIR__ . '/includes/root/permalinks.php' );
#endregion helpers

/**
 * Theme helper includes
 */
#region helpers
require_once realpath( __DIR__ . '/includes/helpers/class-classnames.php' );
require_once realpath( __DIR__ . '/includes/helpers/class-get-image-data.php' );
require_once realpath( __DIR__ . '/includes/helpers/site.php' );
require_once realpath( __DIR__ . '/includes/helpers/string-manipulation.php' );
require_once realpath( __DIR__ . '/includes/helpers/variable-types.php' );
require_once realpath( __DIR__ . '/includes/helpers/array-manipulation.php' );
require_once realpath( __DIR__ . '/includes/helpers/actions-and-filters.php' );
require_once realpath( __DIR__ . '/includes/helpers/taxonomies.php' );
require_once realpath( __DIR__ . '/includes/helpers/blocks.php' );
require_once realpath( __DIR__ . '/includes/helpers/header.php' );
require_once realpath( __DIR__ . '/includes/helpers/frontend.php' );
require_once realpath( __DIR__ . '/includes/helpers/localisation.php' );
require_once realpath( __DIR__ . '/includes/helpers/post-single.php' );
require_once realpath( __DIR__ . '/includes/helpers/metadata.php' );
require_once realpath( __DIR__ . '/includes/helpers/media.php' );
require_once realpath( __DIR__ . '/includes/helpers/pagination.php' );
require_once realpath( __DIR__ . '/includes/helpers/archive.php' );
require_once realpath( __DIR__ . '/includes/helpers/list-alignment.php' );
#endregion helpers

/**
 * Theme multisite includes
 */
#region multisite
require_once realpath( __DIR__ . '/includes/multisite/class-core-site-list.php' );
require_once realpath( __DIR__ . '/includes/multisite/helpers.php' );
#endregion multisite

/**
 * Theme network includes
 */
#region network
require_once realpath( __DIR__ . '/includes/admin/network/class-network-options.php' );
#endregion network

/**
 * Theme admin includes
 */
#region admin
require_once realpath( __DIR__ . '/includes/admin/menu.php' );
require_once realpath( __DIR__ . '/includes/admin/options-helpers.php' );
require_once realpath( __DIR__ . '/includes/admin/cmb2-helpers.php' );
require_once realpath( __DIR__ . '/includes/admin/list-table-filters.php' );
require_once realpath( __DIR__ . '/includes/admin/post-filters.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/header.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/news.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/footer.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/social.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/pop-in.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/analytics.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/localisation.php' );
require_once realpath( __DIR__ . '/includes/admin/theme-options/localisation/class-taxonomy-labels.php' );
require_once realpath( __DIR__ . '/includes/admin/class-accessibility.php' );
require_once realpath( __DIR__ . '/includes/admin/class-permalink-settings.php' );
require_once realpath( __DIR__ . '/includes/admin/user-options.php' );
require_once realpath( __DIR__ . '/includes/admin/settings-general.php' );
#endregion admin

/**
 * Theme setup includes
 */
#region theme setup
require_once realpath( __DIR__ . '/includes/theme-setup/text-domain.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/cookie-control-fix.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/no-js.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/rewrite-rules.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/disable-emoji-support.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/supports.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/wp-head.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/wp-head-cleanup.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/wp-body-open.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/body-class.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/media.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/class-desktop-nav-walker.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/class-mobile-nav-walker.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/navigation.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/scripts-and-styles.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/analytics/google-tag-manager.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/analytics/google-analytics.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/analytics/hotjar.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/analytics/vwo.php' );
require_once realpath( __DIR__ . '/includes/theme-setup/analytics/meta-tags.php' );
#endregion theme setup

/**
 * Theme KSES includes
 */
#region kses
require_once realpath( __DIR__ . '/includes/kses/checkbox-filter.php' );
require_once realpath( __DIR__ . '/includes/kses/wp-kses-post.php' );
require_once realpath( __DIR__ . '/includes/blocks/slider/kses.php' );
#endregion kses

/**
 * Theme includes
 */
#region theme
require_once realpath( __DIR__ . '/includes/post-filters.php' );
#endregion theme

/**
 * Theme block includes
 */
#region blocks
require_once realpath( __DIR__ . '/includes/blocks/block-category.php' );
require_once realpath( __DIR__ . '/includes/blocks/meta.php' );
require_once realpath( __DIR__ . '/includes/blocks/register.php' );
require_once realpath( __DIR__ . '/includes/blocks/remove-stale-metadata.php' );
require_once realpath( __DIR__ . '/includes/blocks/render-header-on-single.php' );
#endregion blocks

#region fse-blocks
require_once realpath( __DIR__ . '/includes/full-site-editing/blocks/register.php' );
#endregion fse-blocks

/**
 * Theme core block modification includes
 */
#region coreblocks
require_once realpath( __DIR__ . '/includes/core-blocks/image/filters.php' );
require_once realpath( __DIR__ . '/includes/core-blocks/button/styles.php' );
require_once realpath( __DIR__ . '/includes/core-blocks/social-icons/styles.php' );
#endregion coreblocks

/**
 * Theme block pattern includes
 */
#region patterns
require_once realpath( __DIR__ . '/includes/block-patterns/pattern-category.php' );
#endregion patterns

/**
 * Theme post type includes
 */
#region post types
require_once realpath( __DIR__ . '/includes/post-types/post-type-helpers.php' );
require_once realpath( __DIR__ . '/includes/post-types/abstract-class-post-type.php' );
require_once realpath( __DIR__ . '/includes/post-types/pop-in.php' );
require_once realpath( __DIR__ . '/includes/post-types/sidebar.php' );
#endregion post types

/**
 * Theme taxonomy includes
 */
#region taxonomies
require_once realpath( __DIR__ . '/includes/taxonomies/taxonomy-filters.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/taxonomy-descriptions.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/abstract-class-taxonomy.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/class-taxonomy-content-types.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/class-taxonomy-locations.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/class-taxonomy-resource-types.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/class-taxonomy-topics.php' );
require_once realpath( __DIR__ . '/includes/taxonomies/custom-fields/precedence.php' );
#endregion taxonomies

/**
 * Theme feature includes
 */
#region features
require_once realpath( __DIR__ . '/includes/features/related-content/class-related-content.php' );
require_once realpath( __DIR__ . '/includes/features/related-content/class-wp-rest-related-content-controller.php' );
#endregion features

/**
 * Theme query filter includes
 */
#region query filters
require_once realpath( __DIR__ . '/includes/query-filters/posts-where.php' );
require_once realpath( __DIR__ . '/includes/query-filters/sort-order.php' );
require_once realpath( __DIR__ . '/includes/query-filters/sticky-posts.php' );
require_once realpath( __DIR__ . '/includes/query-filters/taxonomy-filters.php' );
require_once realpath( __DIR__ . '/includes/query-filters/taxonomy-location-filters.php' );
#endregion query filters

/**
 * Theme search includes
 */
#region search
require_once realpath( __DIR__ . '/includes/features/search/helpers.php' );
require_once realpath( __DIR__ . '/includes/features/search/permalink.php' );
require_once realpath( __DIR__ . '/includes/features/search/post-excerpt.php' );
require_once realpath( __DIR__ . '/includes/features/search/query-vars.php' );
require_once realpath( __DIR__ . '/includes/features/search/class-search-page.php' );
require_once realpath( __DIR__ . '/includes/features/search/class-search-filters.php' );
#endregion search

/**
 * Theme REST API includes
 */
#region rest api
require_once realpath( __DIR__ . '/includes/rest-api/class-category-list.php' );
require_once realpath( __DIR__ . '/includes/rest-api/class-fetch-menus.php' );
require_once realpath( __DIR__ . '/includes/rest-api/class-wordpress-seo.php' );
require_once realpath( __DIR__ . '/includes/rest-api/post-list.php' );
require_once realpath( __DIR__ . '/includes/rest-api/post-data.php' );
require_once realpath( __DIR__ . '/includes/rest-api/users.php' );
#endregion rest api

/**
 * Theme RSS Feed includes
 */
#region rss
require_once realpath( __DIR__ . '/includes/rss/filter-feed-by-term.php' );
#endregion rss

/**
 * Theme SEO includes
 */
#region seo
require_once realpath( __DIR__ . '/includes/seo/base.php' );
require_once realpath( __DIR__ . '/includes/seo/canonical.php' );
require_once realpath( __DIR__ . '/includes/seo/language.php' );
require_once realpath( __DIR__ . '/includes/seo/opengraph.php' );
require_once realpath( __DIR__ . '/includes/seo/primary-term.php' );
require_once realpath( __DIR__ . '/includes/seo/schema-breadcrumbs.php' );
#endregion seo

/**
 * Theme User includes
 */
#region users
require_once realpath( __DIR__ . '/includes/users/class-users-controller.php' );
require_once realpath( __DIR__ . '/includes/users/contact-methods.php' );
require_once realpath( __DIR__ . '/includes/users/meta.php' );
#endregion users

/**
 * Theme WooCommerce includes
 */
#region woocommerce
if ( class_exists( '\WooCommerce', false ) ) {
	// disable WooCommerce block templates -- it breaks lots of things in hybrid
	add_filter( 'woocommerce_has_block_template', '__return_false', 999 );

	require_once realpath( __DIR__ . '/includes/admin/woo/theme-options.php' );

	require_once realpath( __DIR__ . '/includes/woo/helpers.php' );
	require_once realpath( __DIR__ . '/includes/woo/cart.php' );
	require_once realpath( __DIR__ . '/includes/woo/checkout.php' );
	require_once realpath( __DIR__ . '/includes/woo/emails.php' );
	require_once realpath( __DIR__ . '/includes/woo/form-fields.php' );
	require_once realpath( __DIR__ . '/includes/woo/menus.php' );
	require_once realpath( __DIR__ . '/includes/woo/order.php' );
	require_once realpath( __DIR__ . '/includes/woo/product.php' );
	require_once realpath( __DIR__ . '/includes/woo/select-element.php' );
	require_once realpath( __DIR__ . '/includes/woo/templates.php' );
}
#endregion woocommerce

/**
 * Theme MultilingualPress includes
 */
#region multilingualpress
require_once realpath( __DIR__ . '/includes/mlp/helpers.php' );
require_once realpath( __DIR__ . '/includes/mlp/language-selector.php' );
if ( is_multilingualpress_enabled() ) {
	require_once realpath( __DIR__ . '/includes/mlp/polyfills.php' );
	require_once realpath( __DIR__ . '/includes/mlp/metadata.php' );
	require_once realpath( __DIR__ . '/includes/mlp/rest-api.php' );
	require_once realpath( __DIR__ . '/includes/mlp/scheduled-posts.php' );
}
#endregion multilingualpress

// phpcs:enable Squiz.Commenting.InlineComment.WrongStyle,PEAR.Commenting.InlineComment.WrongStyle
