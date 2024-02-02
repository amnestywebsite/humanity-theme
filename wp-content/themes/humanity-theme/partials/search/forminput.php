<?php

/**
 * Search partial, search input field
 *
 * @package Amnesty\Partials
 */

$search_id = bin2hex( random_bytes( 2 ) );

?>
<p>
	<label for="search-<?php echo esc_attr( $search_id ); ?>" class="u-hiddenVisually"><?php echo esc_html( /* translators: [front] screen reader text for search field */ __( 'Search input', 'amnesty' ) ); ?></label>
	<input id="search-<?php echo esc_attr( $search_id ); ?>" class="has-autofocus" type="text" name="s" role="searchbox" placeholder="<?php /* translators: [front] */ esc_attr_e( 'What are you looking for?', 'amnesty' ); ?>" value="<?php echo esc_attr( get_query_var( 's' ) ); ?>">
</p>
