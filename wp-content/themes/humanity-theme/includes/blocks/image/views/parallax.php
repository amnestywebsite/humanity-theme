<?php $image_url = wp_get_attachment_image_url( absint( $attributes['imageID'] ), 'hero-lg' ); ?>
<div class="<?php echo esc_attr( $block_classes ); ?>">
<style>.imageBlock-<?php echo esc_attr( $attributes['identifier'] ); ?> .imageBlock-overlay{background-image:url('<?php echo esc_url( $image_url ); ?>')}</style>
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
<?php else : ?>
	<div class="imageBlock-overlay"></div>
<?php endif; ?>
</div>
<?php if ( $attributes['caption'] ) : ?>
	<div class="<?php echo esc_attr( $caption_classes ); ?>"><?php echo esc_html( $attributes['caption'] ); ?></div>
<?php endif; ?>
