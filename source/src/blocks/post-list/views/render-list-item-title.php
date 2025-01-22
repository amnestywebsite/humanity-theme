<?php

if ( ! $title ) {
	return;
}

$output = esc_html( $title );

if ( isset( $data['link'] ) && (bool) $data['link'] ) {
	$output = sprintf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['link'] ), esc_html( $title ) );
}

?>
<h3 class="linkList-itemTitle"><?php echo wp_kses_post( $output ); ?></h3>
