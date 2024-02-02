<figure class="responsive-iframe wp-block-embed <?php echo esc_attr( $atts['alignment'] ); ?>" <?php $width && printf( 'style="%s"', esc_attr( sprintf( 'max-width:%spx', $width ) ) ); ?>>
	<div class="fluid-iframe" <?php $style && printf( 'style="%s"', esc_attr( $style ) ); ?>>
		<iframe src="<?php echo esc_url( $embed_url ); ?>" title="<?php echo esc_attr( $atts['title'] ); ?>" frameborder="0"></iframe>
	</div>

<?php if ( ! empty( $atts['caption'] ) ) : ?>
	<figcaption>
		<p><?php echo esc_html( $atts['caption'] ); ?></p>
	</figcaption>
<?php endif; ?>
</figure>
