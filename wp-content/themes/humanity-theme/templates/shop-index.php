<?php

/**
 * Template Name: Shop Index
 *
 * @package Amnesty\Templates
 */

if ( ! class_exists( 'WooCommerce', false ) ) {
	wp_safe_redirect( home_url( '', 'https' ) );
	die;
}

get_header( 'shop' );
the_post();

if ( amnesty_post_has_header() ) {
	$header_data = amnesty_get_header_data();

	if ( 'amnesty-core/hero' === $header_data['name'] ) {
		echo wp_kses_post( render_hero_block( $header_data['attrs'] ) );
	} else {
		// phpcs:ignore
		echo \Amnesty\Blocks\amnesty_render_header_block( $header_data['attrs'] );
	}
}

$categories = get_terms(
	[
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
	]
);

?>
<main id="main">
	<section class="section section--small">
		<div class="container article-container">
			<article class="article">
				<header class="article-header">
					<h1 class="article-title"><?php the_content(); ?></h1>
				</header>

				<section class="news-section section section--small section--topSpacing" aria-label="<?php echo esc_attr( /* translators: [front] ARIA List of product categories */ __( 'Product Catalogue', 'amnesty' ) ); ?>">
					<div class="postlist" role="list">
					<?php

					foreach ( $categories as $product_cat ) {
						include locate_template( 'partials/product/category.php' );
					}

					?>
					</div>
				</section>
			</article>
		</div>
	</section>
</main>
<?php get_footer(); ?>
