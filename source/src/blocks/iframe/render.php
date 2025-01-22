<?php

if ( ! $attributes['embedUrl'] ) {
	return;
}

$width      = $attributes['width'];
$height     = $attributes['height'];
$min_height = $attributes['minHeight'];

$style = '';

if ( $width && $height ) {
	$ratio  = $height / $width * 100;
	$ratio  = "{$ratio}%";
	$style .= sprintf( 'padding-top: %s;', $ratio );
}

if ( ! $width && ! $height && ! $min_height ) {
	$min_height = 350;
}

if ( $min_height ) {
	$style .= sprintf( 'min-height: %dpx;', $min_height );
}

$wrapper_attributes = get_block_wrapper_attributes();

?>
<figure <?php echo wp_kses_data( $wrapper_attributes ); ?> style="<?php $width && esc_attr( sprintf( 'max-width:%spx', $width ) ); ?>">
	<div class="fluid-iframe" style="<?php echo esc_attr( $style ); ?>">
		<iframe src="<?php echo esc_url( $attributes['embedUrl'] ); ?>" title="<?php echo esc_attr( $attributes['title'] ); ?>" frameborder="0"></iframe>
	</div>

<?php if ( ! empty( $attributes['caption'] ) ) : ?>
	<figcaption>
		<p><?php echo esc_html( $attributes['caption'] ); ?></p>
	</figcaption>
<?php endif; ?>
</figure>
