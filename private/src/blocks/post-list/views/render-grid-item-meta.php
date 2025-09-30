<?php

$has_data = isset( $data['tag'], $data['tag_link'] ) && (bool) $data['tag'];

if ( ! $has_data ) {
	return;
}

$output = esc_html( $data['tag'] );

if ( (bool) $data['tag_link'] && (bool) $data['tag_link'] ) {
	$output = sprintf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['tag_link'] ), esc_html( $data['tag'] ) );
}

?>

<span class="grid-itemMeta"><?php echo wp_kses_post( $output ); ?></span>
