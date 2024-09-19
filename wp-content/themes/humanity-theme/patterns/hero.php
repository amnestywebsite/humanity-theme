<?php

/**
 * Title: Hero Pattern
 * Description: Hero pattern for the theme
 * Slug: amnesty/hero
 * Inserter: yes
 */

$hero_data = amnesty_get_hero_data();

if ( empty( $hero_data['attrs']['title'] ) ) {
	$hero_data['attrs']['title'] = amnesty_get_meta_field( '_hero_title' ) ?: get_the_title();
}

if ( empty( $hero_data['attrs']['imageID'] ) ) {
	$hero_data['attrs']['imageID'] = $hero_data['attrs']['featuredImageId'] ?? get_post_thumbnail_id();
}

if ( ! empty( $hero_data['attrs']['imageID'] ) ) :
	?>

	<!-- wp:amnesty-core/hero <?php echo wp_kses_data( wp_json_encode( $hero_data['attrs'], JSON_UNESCAPED_UNICODE ) ); ?> -->
	<?php echo wp_kses_post( $hero_data['content'] ); ?>
	<!-- /wp:amnesty-core/hero -->

	<?php
	return;
endif;
?>
<!-- wp:group {"tagName":"header","className":"article-header","layout":{"type":"constrained"}} -->
<header class="wp-block-group article-header">
	<!-- wp:post-title {"level":1,"className":"article-title"} /-->
</header>
<!-- /wp:group -->
