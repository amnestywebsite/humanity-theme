<?php

/**
 * Title: Page Content Pattern
 * Description: Page content pattern for the theme
 * Slug: amnesty/page-content
 * Inserter: no
 */

$sidebar_is_enabled = amnesty_get_meta_field( '_disable_sidebar' ) !== '1';
$sidebar_is_present = amnesty_is_sidebar_available();
$class_name         = $sidebar_is_enabled && $sidebar_is_present ? 'has-sidebar' : '';

?><!-- wp:group {"tagName":"article","className":"article <?php print esc_attr( $class_name ); ?>"} -->
<article class="wp-block-group article <?php print esc_attr( $class_name ); ?>">
<!-- wp:group {"tagName":"section","className":"article-content"} -->
<section class="wp-block-group article-content"><!-- wp:post-content /--></section>
<!-- /wp:group -->
</article>
<!-- /wp:group -->
