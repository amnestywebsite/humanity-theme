<?php $image_size = 'wide' === $attributes['style'] ? 'action-wide' : 'post-half'; ?>
<figure class="<?php echo esc_attr( $block_classes ); ?>">
	<span class="customCard-label"><?php echo esc_html( $attributes['label'] ); ?></span>
	<div class="customCard-figure">
		<?php echo wp_get_attachment_image( absint( $attributes['imageID'] ), $image_size, false, [ 'class' => 'customCard-image aiic-ignore' ] ); ?>
	</div>
	<figcaption class="customCard-content">
	<p><?php echo esc_html( $attributes['content'] ); ?></p>
	<a class="btn btn--fill btn--large" href="<?php echo esc_url( $attributes['scrollLink'] ?: $attributes['link'] ); ?>"><?php echo esc_html( $attributes['linkText'] ); ?></a>
	</figcaption>
</figure>
