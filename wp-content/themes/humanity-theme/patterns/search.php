<?php

/**
 * Title: Search pattern
 * Description: Search pattern for the theme
 * Slug: amnesty/search
 * Inserter: no
 */

?>

<!-- wp:group {"tagName":"div","className":"wp-block-group container search-container has-gutter"} -->
<div class="wp-block-group container search-container has-gutter">
<!-- wp:pattern {"slug":"amnesty/horizontal-search"} -->

<!-- wp:amnesty-core/block-section {"className":"section search-results section--tinted" "aria-label":"<?php /* translators: [front] ARIA */ esc_attr_e( 'Search results', 'amnesty' ); ?>"} -->

<!-- wp:pattern {"slug":"amnesty/archive-header"} -->

<?php
do_action( 'amnesty_before_search_results' );

while ( have_posts() ) {
	the_post();
	?>
	<!-- wp:pattern {"slug":"amnesty/post-search"} -->
	<?php
}

do_action( 'amnesty_after_search_results' );

?>
<!-- /wp:amnesty-core/block-section -->
</div>
<!-- /wp:group -->
