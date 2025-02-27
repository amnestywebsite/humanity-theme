<?php

// used in views
$block_classes = classnames(
    'imageBlock',
    [
        // Conditional class for identifier and parallax
        sprintf( 'imageBlock-%s', esc_attr( $attributes['identifier'] ) ) => (bool) $attributes['parallax'],

        // Fixed style class
        'imageBlock--fixed' => 'fixed' === $attributes['style'],

        // Video type class
        'has-video' => 'video' === $attributes['type'],

        // Parallax class
        'has-parallax' => (bool) $attributes['parallax'],

        // Alignment class, e.g., alignleft, alignright, aligncenter
        isset( $attributes['align'] ) ? sprintf( 'align%s', esc_attr( $attributes['align'] ) ) : ''
    ]
);


// used in views
$caption_classes = classnames(
	'imageBlock-caption',
	[
		sprintf( 'align%s', $attributes['align'] ) => 'default' !== $attributes['align'],
	]
);

?>

<div class="<?php echo esc_attr( $block_classes ); ?>">
<?php if ( ! $attributes['type'] ) : ?>
	<?php echo wp_get_attachment_image( absint( $attributes['imageID'] ), 'fixed' === $attributes['style'] ? 'image-block' : 'full' ); ?>
<?php endif; ?>
<?php if ( 'video' === $attributes['type'] ) : ?>
	<video class="imageBlock-video" autoPlay loop muted>
		<source src="<?php echo esc_url( $attributes['videoURL'] ); ?>">
	</video>
<?php endif; ?>
<?php if ( $attributes['hasOverlay'] ) : ?>
	<div class="imageBlock-overlay">
		<div class="imageBlock-title"><?php echo esc_html( $attributes['title'] ); ?></div>
		<div class="imageBlock-content">
			<p><?php echo esc_html( $attributes['content'] ); ?></p>
		</div>
		<div class="imageBlock-buttonWrapper">
		<?php foreach ( $attributes['buttons'] as $button ) : ?>
			<a class="btn btn--white" href="<?php echo esc_url( $button['url'] ); ?>"><?php echo esc_html( $button['text'] ); ?></a>
		<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
</div>
<?php if ( $attributes['caption'] ) : ?>
	<div class="<?php echo esc_attr( $caption_classes ); ?>"><?php echo esc_html( $attributes['caption'] ); ?></div>
<?php endif; ?>
