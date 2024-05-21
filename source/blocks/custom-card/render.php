<?php

// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
$block_classes = classnames(
	'customCard',
	$attributes['className'],
	[
		sprintf( 'align%s', $attributes['align'] ) => (bool) $attributes['align'],
		'actionBlock--wide' => 'wide' === $attributes['style'],
		'is-centred'        => (bool) $attributes['centred'],
	]
);
// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

$button_classes = classnames( 'btn', 'btn--fill', 'btn--large' );

$image_size = 'post-half';
if ( 'wide' === $attributes['style'] ) {
	$image_size = 'action-wide';
}

?>
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
