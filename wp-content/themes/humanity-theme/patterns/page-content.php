<?php

/**
 * Title: Page Content Pattern
 * Description: Page content pattern for the theme
 * Slug: amnesty/page-content
 * Inserter: no
 */

$sidebar_is_enabled = amnesty_get_meta_field( '_disable_sidebar' ) !== '1';
$class_name         = $sidebar_is_enabled ? 'has-sidebar' : '';

if ( amnesty_post_has_hero() ) {
	add_filter( 'the_content', 'amnesty_remove_first_hero_from_content', 1 );
}

?>
<!-- wp:group {"tagName":"article","className":"article <?php print esc_attr( $class_name ); ?>","layout":{"type":"constrained"}} -->
<article class="wp-block-group article <?php print esc_attr( $class_name ); ?>">
	<!-- wp:group {"tagName":"section","className":"article-content","layout":{"type":"constrained"}} -->
	<section class="wp-block-group article-content">
		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
	</section>
	<!-- /wp:group -->
</article>
<!-- /wp:group -->
