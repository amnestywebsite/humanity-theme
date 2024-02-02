<div class="download-block <?php echo esc_attr( $align ); ?>">
<?php

printf(
	'<a class="btn btn--download %s" href="%s" download="%s" target="_blank" role="button">%s</a>',
	esc_attr( $colour ),
	esc_url( $url ),
	esc_attr( $name ),
	esc_html( $text )
);

?>
</div>
