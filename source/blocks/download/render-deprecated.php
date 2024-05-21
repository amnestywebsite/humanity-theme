<?php

$file_id = amnesty_get_meta_field( 'download_id' ) ?: false;

if ( ! $file_id ) {
	return;
}

/* translators: [front] Download block https://wordpresstheme.amnesty.org/blocks/015-download-resource/ */
$button_text = amnesty_get_meta_field( 'download_text' ) ?: __( 'Download', 'amnesty' );
$file_url    = wp_get_attachment_url( $file_id );
$file_name   = explode( '/', $file_url );
$file_name   = array_pop( $file_name );

?>
<div>
	<a
		class="btn btn--dark btn--download"
		href="<?php echo esc_url( $file_url ); ?>"
		target="_blank"
		download="<?php echo esc_attr( $file_name ); ?>"
		role="button"
	><?php echo esc_html( $button_text ); ?></a>
</div>
