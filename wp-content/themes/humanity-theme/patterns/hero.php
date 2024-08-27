<?php

/**
 * Title: Hero Pattern
 * Description: Hero pattern for the theme
 * Slug: amnesty/hero
 * Inserter: yes
 */

$hero_title = amnesty_get_meta_field( '_hero_title' );

if ( amnesty_post_has_hero() ) {
	$hero_data  = amnesty_get_hero_data();
	$hero_title = $hero_data['attrs']['title'] ?? false;
}

if ( $hero_title ) {
	return;
}

?>
<!-- wp:group {"tagName":"header","className":"article-header","layout":{"type":"constrained"}} -->
<header class="wp-block-group article-header">
	<!-- wp:post-title {"level":1,"className":"article-title"} /-->
</header>
<!-- /wp:group -->
