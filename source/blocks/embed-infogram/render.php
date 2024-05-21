<?php

if ( ! $atttributes['identifier'] ) {
	return;
}

?>
<div
	class="infogram-embed"
	data-id="<?php echo esc_attr( $attributes['identifier'] ); ?>"
	data-type="<?php echo esc_attr( $attributes['type'] ); ?>"
	data-title="<?php echo esc_attr( $attributes['title'] ); ?>"
></div>
