<?php

$taxonomies = amnesty_get_object_taxonomies( 'post', 'objects' );
$form_url   = amnesty_search_url();

if ( is_category() ) {
	unset( $taxonomies['category'] );

	// pass category archive id to form URI
	$form_url = add_query_arg( [ 'qcategory' => get_queried_object_id() ], $form_url );
}

if ( ! $taxonomies ) {
	return;
}
?>
<div class="section section--tinted">
	<section class="postlist-categoriesContainer" style="display: flex;" data-slider>
		<form id="filter-form" class="news-filters" action="<?php echo esc_url( $form_url ); ?>">
			<?php require locate_template( 'partials/forms/taxonomy-filters.php' ); ?>
		</form>
	</section>
</div>
