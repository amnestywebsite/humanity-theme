<?php

/**
 * Title: Search filters pattern
 * Description: Search filters pattern for the theme
 * Slug: amnesty/search-filters
 * inserter: no
 */

$taxonomies = amnesty_get_object_taxonomies( 'post', 'objects' );

if ( is_category() ) {
	unset( $taxonomies['category'] );
}

if ( ! $taxonomies ) {
	return;
}

?>

<!-- wp:group {"tagName":"div","className":"section section--small section--dark postlist-categoriesContainer"} -->
<div class="wp-block-group section section--small section--dark postlist-categoriesContainer">
<!-- wp:pattern {"slug":"amnesty/taxonomy-filter"} /-->
</div>
<!-- /wp:group -->
