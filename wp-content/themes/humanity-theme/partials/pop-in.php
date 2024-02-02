<?php

/**
 * Global partial, pop-in
 *
 * @package Amnesty\Partials
 */

if ( ! amnesty_feature_is_enabled( 'pop-in' ) ) {
	return;
}

$active_pop_in   = false;
$pop_in_settings = get_option( 'amnesty_pop_in_options_page' );
if ( ! empty( $pop_in_settings['active_pop_in'][0] ) ) {
	$active_pop_in = absint( $pop_in_settings['active_pop_in'][0] );
}

if ( ! $active_pop_in ) {
	return;
}

$pop_in     = get_post( $active_pop_in );
$show_title = 'yes' === get_post_meta( $pop_in->ID, 'renderTitle', true );

?>

<aside id="pop-in" class="u-textCenter pop-in is-closed">
	<button id="pop-in-close" class="pop-in-close">X</button>
	<div class="section section--small">
		<div class="container container--small">
		<?php if ( $show_title ) : ?>
			<h1><?php echo esc_html( apply_filters( 'the_title', $pop_in->post_title ) ); ?></h1>
		<?php endif; ?>
			<?php echo wp_kses_post( apply_filters( 'the_content', $pop_in->post_content ) ); ?>
		</div>
	</div>
</aside>
