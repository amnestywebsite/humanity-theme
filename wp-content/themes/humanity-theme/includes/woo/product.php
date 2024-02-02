<?php

// phpcs:disable PSR12.Files.FileHeader.IncorrectOrder

declare( strict_types = 1 );

/**
 * Remove suggested price text
 */
add_filter( 'woocommerce_nyp_suggested_price_html', '__return_empty_string' );

/**
 * Remove reset variations link
 */
add_filter( 'woocommerce_reset_variations_link', '__return_empty_string' );

/**
 * Remove the WC sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Prevent shuffling of related products
 */
add_filter( 'woocommerce_product_related_posts_shuffle', '__return_false' );
