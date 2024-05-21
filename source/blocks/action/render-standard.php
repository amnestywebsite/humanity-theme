<?php

$block_classes = classnames(
	'actionBlock',
	[
		'is-centred' => $attributes['centred'],
	] 
);

?>
<figure class="<?php echo esc_attr( $block_classes ); ?>">
	<div class="actionBlock-figure">
		<?php

		if ( 0 !== absint( $attributes['imageID'] ) ) {
			echo wp_get_attachment_image( absint( $attributes['imageID'] ), 'action-standard', false, [ 'class' => 'actionBlock-image aiic-ignore' ] );
		} elseif ( '' !== $attributes['imageURL'] ) {
			printf( '<img class="actionBlock-image aiic-ignore" src="%s" alt="%s">', esc_url( $attributes['imageURL'] ), esc_attr( $attributes['imageAlt'] ) );
		}

		?>
		<span class="actionBlock-label"><?php echo esc_html( $attributes['label'] ); ?></span>
	</div>
	<figcaption class="actionBlock-content">
		<p><?php echo esc_html( $attributes['content'] ); ?></p>
		<a class="btn btn--fill btn--large" href=<?php echo esc_url( $attributes['link'] ); ?>><?php echo esc_html( $attributes['linkText'] ); ?></a>
	</figcaption>
</figure>
