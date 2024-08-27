<?php

/**
 * Title: Article Pattern
 * Description: Article pattern for the theme
 * Slug: amnesty/article
 * Inserter: yes
 */

$sidebar_is_enabled = amnesty_get_meta_field('_disable_sidebar') !== '1';
$class_name         = $sidebar_is_enabled ? 'has-sidebar' : '';

?><!-- wp:group {"tagName":"article","className":"article <?php print $class_name; ?>"} -->
<article class="wp-block-group article <?php print $class_name; ?>">
<!-- wp:pattern {"slug":"amnesty/hero"} /-->
<!-- wp:group {"tagName":"section","className":"article-content"} -->
<section class="wp-block-group article-content"><!-- wp:post-content /--></section>
<!-- /wp:group -->
</article>
<!-- /wp:group -->
