<?php

if ( ! $title ) {
	return;
}

$output = sprintf( '<span>%s</span>', esc_html( $title ) );

if ( isset( $data['link'] ) && (bool) $data['link'] ) {
	$output = sprintf( '<a href="%s" tabindex="0">%s</a>', esc_url( $data['link'] ), esc_html( $title ) );
}

?>

<h3 class="grid-itemTitle"><?php echo wp_kses_post( $output ); ?></h3>
