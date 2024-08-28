<?php

/**
 * Title: Form input pattern
 * Description: Form input pattern for the theme
 * Slug: amnesty/form-input
 * Inserter: no
 */

$search_id = bin2hex( random_bytes( 2 ) );

?>
<!-- wp:paragraph -->
<label for="search-<?php echo esc_attr( $search_id ); ?>" class="wp-block-group u-hiddenVisually"><?php echo esc_html( /* translators: [front] screen reader text for search field */ __( 'Search input', 'amnesty' ) ); ?></label>
<input id="search-<?php echo esc_attr( $search_id ); ?>" class="wp-block-group has-autofocus" type="text" name="s" role="searchbox" placeholder="<?php /* translators: [front] */ esc_attr_e( 'What are you looking for?', 'amnesty' ); ?>" value="<?php echo esc_attr( get_query_var( 's' ) ); ?>">
<!-- /wp:paragraph -->
