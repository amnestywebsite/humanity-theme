<?php

/**
 * Title: Attachment Sidebar
 * Description: Output sidebar on attachment template(s)
 * Slug: amnesty/attachment-sidebar
 * Inserter: no
 */

$sidebar_id = amnesty_get_option( '_default_sidebar' )[0] ?? 0;
$sidebar    = get_post( $sidebar_id );

?>

<!-- wp:group {"tagName":"aside","className":"article-sidebar"} -->
<aside class="wp-block-group article-sidebar"><?php echo wp_kses_post( apply_filters( 'the_content', $sidebar?->post_content ?? '' ) ); ?></aside>
<!-- /wp:group -->
