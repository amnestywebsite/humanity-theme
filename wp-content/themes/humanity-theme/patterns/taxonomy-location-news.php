<?php

/**
 * Title: Locations Taxonomy news
 * Description: Outputs posts for a locations taxonomy term
 * Slug: amnesty/taxonomy-location-news
 * Inserter: no
 */

$template_type = get_term_meta( get_queried_object_id(), 'type', true ) ?: 'default';

do_action( 'amnesty_location_template_before_news', $template_type );

$category_tax_slug = get_option( 'amnesty_category_slug' ) ?: 'category';
$location_tax_slug = get_option( 'amnesty_location_slug' ) ?: 'location';

$news_term = get_term_by( 'slug', 'news', 'category' );
$news_link = add_query_arg(
	[
		'q' . $category_tax_slug => $news_term->term_id,
		'q' . $location_tax_slug => get_queried_object()->slug,
	],
	amnesty_search_url()
);

$query_args = [
	'queryId' => 1,
	'query'   => [
		'perPage'  => 3,
		'pages'    => 0,
		'offset'   => 0,
		'postType' => 'post',
		'order'    => 'desc',
		'orderBy'  => 'date',
		'author'   => '',
		'search'   => '',
		'exclude'  => [], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- required parameter for the block
		'sticky'   => 'exclude',
		'inherit'  => true,
		'taxQuery' => [
			'location' => [ get_queried_object_id() ],
		],
	],
];

// admin area ignores some args
if ( is_admin() ) {
	$query_args['query']['inherit']  = false;
	$query_args['query']['taxQuery'] = [
		'category' => [ get_term_by( 'slug', 'news', 'category' )?->term_id ],
	];
}

?>
<!-- wp:group {"tagName":"section","className":"section"} -->
<section id="news" class="wp-block-group section">
	<!-- wp:group {"tagName":"div","className":"container has-gutter"} -->
	<div class="wp-block-group container has-gutter">
		<!-- wp:group {"tagName":"header"} -->
		<header class="wp-block-group">
			<!-- wp:heading {"textAlign":"center"} -->
			<h2 class="wp-block-heading has-text-align-center"><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'News', 'news section heading', 'aitc' ); ?></h2>
			<!-- /wp:heading -->
		</header>
		<!-- /wp:group -->

		<!-- wp:query <?php echo wp_kses_data( wp_json_encode( $query_args ) ); ?> -->
		<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"customOverlayColor":"#FFF","isUserOverlayColor":true,"minHeight":360,"contentPosition":"bottom left","isDark":false,"layout":{"type":"constrained"}} -->
		<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left" style="min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#FFF"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group"><!-- wp:post-terms {"term":"category","className":"grid-itemMeta","style":{"spacing":{"padding":{"left":"var:preset|spacing|quarter","right":"var:preset|spacing|quarter"}}},"fontSize":"regular"} /--></div>
		<!-- /wp:group -->

		<!-- wp:post-title {"level":3,"isLink":true,"className":"grid-itemTitle","fontSize":"medium"} /--></div></div>
		<!-- /wp:cover -->
		<!-- /wp:post-template --></div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"tagName":"footer","className":"wp-block-group section"} -->
	<footer class="wp-block-group section">
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons"><!-- wp:button {"className":"btn--topSpacing is-style-light"} -->
		<div class="wp-block-button btn--topSpacing is-style-light"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $news_link ); ?>">
		<span><?php /* translators: [front] Countries Locations Page */ echo esc_html_x( 'View all news', 'new button', 'aitc' ); ?></span></a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->
	</footer>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->

<?php do_action( 'amnesty_location_template_after_news', $template_type ); ?>
