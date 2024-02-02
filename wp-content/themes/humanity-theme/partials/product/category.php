<?php

/**
 * WooCommerce partial, product category
 *
 * @package Amnesty\Plugins\WooCommerce\Partials
 */

if ( ! isset( $product_cat ) ) {
	return;
}

$featured_image = wp_get_attachment_image_url( get_term_meta( $product_cat->term_taxonomy_id, 'thumbnail_id', true ), 'wc-thumb' );

if ( ! $featured_image ) {
	$featured_image = wc_placeholder_img_src( 'wc-thumb' );
}

$category_title = apply_filters( 'the_title', $product_cat->name );
$category_link  = get_term_link( $product_cat );
$category_link  = is_wp_error( $category_link ) ? get_permalink( wc_get_page_id( 'shop' ) ) : $category_link;

$aria_label = format_for_aria_label( $category_title );
$aria_label = sprintf( /* translators: [front] ARIA Donate %s: the product category name */ __( 'Product Category: %s', 'amnesty' ), $aria_label );

?>
<article class="post postImage--small" role="listitem" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<figure class="post-figure">
		<a href="<?php echo esc_url( $category_link ); ?>">
			<img src="<?php echo esc_url( $featured_image ); ?>" alt="">
		</a>
	</figure>
	<div class="post-content">
		<header class="post-header">
			<h1 class="post-title"><span><a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $category_title ); ?></a></span></h1>
		</header>
	</div>
</article>
