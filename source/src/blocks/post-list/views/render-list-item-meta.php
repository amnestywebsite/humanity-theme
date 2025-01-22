<?php

if ( ! isset( $data['tag'], $data['tag_link'] ) ) {
	return;
}

if ( ! $data['tag'] ) {
	return;
}

$output = esc_html( $data['tag'] );

if ( isset( $data['tag_link'] ) && (bool) $data['tag_link'] ) {
	$output = sprintf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['tag_link'] ), esc_html( $data['tag'] ) );
}

?>
<span class="linkList-itemMeta"><?php echo wp_kses_post( $output ); ?></span>
