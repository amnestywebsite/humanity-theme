<?php

$alignment = $attributes['alignment'] ?? 'left';
$background = $attributes['imageId'] ?? '';
$cta_link = $attributes['callToActionLink'] ?? '';
$cta_text = $attributes['callToActionText'] ?? '';
$content = $attributes['content'] ?? '';
$hide_content = $attributes['hideContent'] ?? false;
$id = $attributes['id'] ?? '';
$subtitle = $attributes['subheading'] ?? '';
$timeline_content = $attributes['timelineContent'] ?? '';
$title = $attributes['title'] ?? '';

$slide_classes = classnames(
	'slide',
	[
		"is-{$alignment}-aligned"      => $alignment,
	]
);

$background_url = wp_get_attachment_image_url( $background, 'full' );

$slide_style = '';

// Build style background image if background image is set
if ( $background_url ) {
	$slide_style = "background-image: url({$background_url});";
}

?>
<div id="slide-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $slide_classes ); ?>" style="<?php echo esc_attr($slide_style) ?>" tabindex="0">
<?php if ( $timeline_content ) : ?>
	<div class="slide-timelineContent">
		<div class="slide-timelineContent-inner"><?php echo wp_kses_post( $timeline_content ); ?></div>
	</div>
<?php endif; ?>

<?php if ( ! $hide_content ) : ?>
	<div class="slide-contentWrapper" data-tooltip="<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ AM not seen this in use, might be to close a gallery */ esc_attr_e( 'Tap here to return to gallery', 'amnesty' ); ?>">
		<div class="slide-contentContainer">
		<?php if ( $title ) : ?>
			<h1 class="slide-title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php endif; ?>

		<?php if ( $subtitle ) : ?>
			<h2 class="slide-subtitle"><?php echo wp_kses_post( $subtitle ); ?></h2>
		<?php endif; ?>

			<div class="slide-content">
			<?php if ( $content ) : ?>
				<div><?php echo wp_kses_post( $content ); ?></div>
			<?php endif; ?>

			<a class="btn" href="<?php echo esc_url( $cta_link ); ?>"><?php echo wp_kses_post( $cta_text ); ?></a>
			</div>
		</div>
	</div>
<?php endif; ?>
</div>
