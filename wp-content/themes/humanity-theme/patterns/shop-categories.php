<?php

/**
 * Title: Shop Categories
 * Description: Render category list on shop pages
 * Slug: amnesty/shop-categories
 * Inserter: no
 */

$categories = get_terms(
	[
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
	]
);

if ( ! is_array( $categories ) ) {
	return;
}

foreach ( $categories as $product_cat ) :
	$image_id = get_term_meta( $product_cat->term_taxonomy_id, 'thumbnail_id', true ) ?: get_option( 'woocommerce_placeholder_image', 0 );

	$category_title = apply_filters( 'the_title', $product_cat->name );
	$category_link  = get_term_link( $product_cat );
	$category_link  = is_wp_error( $category_link ) ? get_permalink( wc_get_page_id( 'shop' ) ) : $category_link;

	?>
	<!-- wp:group {"tagName":"article","className":"post postImage--small"} -->
	<article class="wp-block-group post postImage--small">
		<?php if ( $image_id ) : ?>
		<!-- wp:image {"id":<?php echo absint( $image_id ); ?>,"sizeSlug":"wc-thumb","className":"post-figure"} -->
		<figure class="wp-block-image size-wc-thumb"><img alt="" class="wp-image-<?php echo absint( $image_id ); ?>"/></figure>
		<!-- /wp:image -->
		<?php endif; ?>
		<!-- wp:group {"tagName":"div","className":"post-content"} -->
		<div class="wp-block-group post-content">
			<!-- wp:group {"tagName":"header","className":"post-header"} -->
			<header class="wp-block-group post-header">
				<!-- wp:heading {"className":"post-title"} -->
				<h2 class="wp-block-heading post-title">
					<span>
						<a href="<?php echo esc_url( amnesty_term_link( $product_cat, get_permalink( wc_get_page_id( 'shop' ) ) ) ); ?>">
							<?php echo esc_html( $category_title ); ?>
						</a>
					</span>
				</h2>
				<!-- /wp:heading -->
			</header>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</article>
	<!-- /wp:group -->
	<?php

endforeach;
