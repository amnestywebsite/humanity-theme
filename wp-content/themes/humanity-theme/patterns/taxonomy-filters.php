<?php

/**
 * Title: Taxonomy filters pattern
 * Description: Taxonomy filters pattern for the theme
 * Slug: amnesty/taxonomy-filters
 * Inserter: no
 */

if ( ! isset( $taxonomies ) ) {
	return;
}

?>
<!-- wp:group {"tagName":"div","className":"wp-block-group taxonomyArchive-filters"} -->
<div class="wp-block-group taxonomyArchive-filters" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Filter results by topic', 'amnesty' ); ?>">
<?php

foreach ( $taxonomies as $tax_item ) {
	amnesty_render_custom_select(
		[
			/* translators: [front] AM not sure yet */
			'label'    => $tax_item->label,
			'name'     => "q{$tax_item->name}",
			'active'   => query_var_to_array( "q{$tax_item->name}" ),
			'options'  => amnesty_taxonomy_to_option_list( $tax_item ),
			'multiple' => true,
		]
	);
}

?>
</div>
<!-- /wp:group -->
<!-- wp:group {"tagName":"button","className":"wp-block-group btn btn--dark"} -->
<button id="search-filters-submit" class="wp-block-group btn btn--dark"><?php /* translators: [front] search results left hand side of page button */ esc_html_e( 'Apply', 'amnesty' ); ?></button>
<!-- /wp:group -->
