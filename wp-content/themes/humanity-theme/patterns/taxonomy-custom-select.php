<?php

/**
 * Title: Custom select pattern
 * Description: Custom select pattern for the theme
 * Slug: amnesty/custom-select
 * Inserter: no
 */

 if ( ! isset( $taxonomies ) ) {
	return;
}

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
