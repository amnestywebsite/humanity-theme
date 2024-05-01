<?php

$extra_attrs = [];

if ( $attributes['width'] ) {
	$extra_attrs['style'] = sprintf( 'max-width:%s%%', $attributes['width'] );
}

$wrapper_style = '';
if ( $attributes['minHeight'] ) {
	$wrapper_style .= sprintf( 'padding-top:%1$dpx;min-height:%1$dpx;', $attributes['minHeight'] );
}

?>
<figure <?php echo get_block_wrapper_attributes( $extra_attrs ); // phpcs:ignore ?>>
	<div class="iframe-wrapper" style="<?php echo esc_attr( $wrapper_style ); ?>">
		<iframe src="<?php echo esc_url( $attributes['embedUrl'] ); ?>" title="<?php echo esc_attr( $attributes['title'] ); ?>" frameborder="0"></iframe>
	</div>

<?php if ( $attributes['caption'] ) : ?>
	<figcaption class="iframe-caption-wrapper">
		<p><?php echo esc_html( $attributes['caption'] ); ?></p>
	</figcaption>
<?php endif; ?>
</figure>
