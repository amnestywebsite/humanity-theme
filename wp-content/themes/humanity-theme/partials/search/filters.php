<?php

/**
 * Search partial, taxonomy filters
 *
 * @package Amnesty\Partials
 */

$taxonomies = amnesty_get_object_taxonomies( 'post', 'objects' );

if ( is_category() ) {
	unset( $taxonomies['category'] );
}

if ( ! $taxonomies ) {
	return;
}

?>
<div class="section section--small section--dark postlist-categoriesContainer">
	<?php require locate_template( 'partials/forms/taxonomy-filters.php' ); ?>
</div>
