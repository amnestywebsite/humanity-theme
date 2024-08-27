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
<!-- wp:group {"tagName":"div","className":"wp-block-group u-hiddenVisually"} -->
<label for="search-<?php echo esc_attr( $search_id ); ?>" class="wp-block-group u-hiddenVisually"><?php echo esc_html( /* translators: [front] screen reader text for search field */ __( 'Search input', 'amnesty' ) ); ?></label>
<!-- /wp:group -->
<!-- wp:group {"tagName":"input","className":"wp-block-group has-autofocus"} -->
<input id="search-<?php echo esc_attr( $search_id ); ?>" class="wp-block-group has-autofocus" type="text" name="s" role="searchbox" placeholder="<?php /* translators: [front] */ esc_attr_e( 'What are you looking for?', 'amnesty' ); ?>" value="<?php echo esc_attr( get_query_var( 's' ) ); ?>">
<!-- /wp:group -->
<!-- /wp:paragraph -->
