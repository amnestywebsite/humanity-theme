<?php

if ( function_exists( 'is_shop' ) && is_shop() ) {
	return;
}

if ( is_tax() ) {
	get_template_part( 'partials/taxonomy/hero' );
	return;
}

if ( ! amnesty_post_has_hero() ) {
	return;
}

$hero_data = amnesty_get_hero_data();

if ( ! array_filter( $hero_data ) ) {
	return;
}

add_filter( 'the_content', 'amnesty_remove_first_hero_from_content', 0 );

?>

<!-- wp:amnesty-core/hero <?php echo wp_kses_data( wp_json_encode( $hero_data['attrs'] ) ); ?> -->
<?php echo wp_kses_post( $hero_data['content'] ); ?>
<!-- /wp:amnesty-core/hero -->
