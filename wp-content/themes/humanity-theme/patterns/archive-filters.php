<?php

/**
 * Title: Archive Filters
 * Description: Outputs filters for post type archive pages
 * Slug: amnesty/archive-filters
 * Inserter: no
 */

$taxonomies = amnesty_get_object_taxonomies( 'post', 'objects' );

if ( ! $taxonomies ) {
	return;
}

$form_url = amnesty_search_url();

if ( is_category() ) {
	unset( $taxonomies['category'] );

	// pass category archive id to form URI
	$form_url = add_query_arg( [ 'qcategory' => get_queried_object_id() ], $form_url );
}

?>
<!-- wp:group {"tagName":"div","className":"section section--tinted"} -->
<div class="wp-block-group section section--tinted">
	<!-- wp:group {"tagName":"section","className":"postlist-categoriesContainer"} -->
	<section class="wp-block-group postlist-categoriesContainer" style="display: flex;" data-slider>
		<!-- wp:group {"tagName":"form","className":"news-filters","htmlId":"filter-form","action":"<?php echo esc_url( $form_url ); ?>"} -->
		<form id="filter-form" class="news-filters" action="<?php echo esc_url( $form_url ); ?>">
			<!-- wp:group {"tagName":"div","className":"taxonomyArchive-filters"} -->
			<div class="taxonomyArchive-filters">
			<?php

			foreach ( $taxonomies as $tax_item ) {
				$block_args = [
					'label'    => $tax_item->label,
					'name'     => "q{$tax_item->name}",
					'active'   => query_var_to_array( "q{$tax_item->name}" ),
					'options'  => amnesty_taxonomy_to_option_list( $tax_item ),
					'multiple' => true,
				];

				printf( '<!-- wp:amnesty-core/custom-select %s /-->', wp_kses_data( wp_json_encode( $block_args ) ) );
			}

			?>
			</div>
			<!-- /wp:group -->
			<button id="search-filters-submit" class="btn btn--dark"><?php /* translators: [front] search results left hand side of page button */ esc_html_e( 'Apply', 'amnesty' ); ?></button>
		</form>
		<!-- /wp:group -->
	</section>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
