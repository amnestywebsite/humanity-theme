<?php

/**
 * Title: Post Hero
 * Description: Outputs the post's hero, if any
 * Slug: amnesty/post-hero
 * Inserter: no
 */

if ( ! amnesty_post_has_hero() ) {
	// deprecated
	if ( amnesty_post_has_header() ) {
		echo '<!-- wp:pattern {"slug":"amnesty/post-header"} /-->';
	}

	return;
}

$hero_data = amnesty_get_hero_data();

if ( ! array_filter( $hero_data ) ) {
	return;
}

if ( ! is_admin() ) {
	add_filter( 'the_content', 'amnesty_remove_first_hero_from_content', 0 );
}

?>

<!-- wp:amnesty-core/hero <?php echo wp_kses_data( wp_json_encode( $hero_data['attrs'] ) ); ?> -->
<?php echo wp_kses_post( $hero_data['content'] ); ?>
<!-- /wp:amnesty-core/hero -->
