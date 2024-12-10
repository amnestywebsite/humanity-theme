<?php
// Attributes

$alignment = $attributes['alignment'] ?? 'left';
$background_image = $attributes['imageId'] ?? '';
$content_background = $attributes['background'] ?? '';
$cta_link = $attributes['callToActionLink'] ?? '';
$cta_text = $attributes['callToActionText'] ?? '';
$content = $attributes['content'] ?? '';
$hide_content = $attributes['hideContent'] ?? false;
$id = $attributes['id'] ?? '';
$subtitle = $attributes['subheading'] ?? '';
$timeline_content = $attributes['timelineContent'] ?? '';
$title = $attributes['title'] ?? '';

// Context
$has_content = $block->context['amnesty-core/slider/hasContent'];

$slide_classes = classnames(
	'slide',
	[
		"is-{$alignment}-aligned"      => $alignment,
		"has-{$content_background}-background" => $content_background,
	]
);

$background_image_url = wp_get_attachment_image_url( $background_image, 'full' );

$slide_style = '';

// Build style background image if background image is set
if ( $background_image_url ) {
	$slide_style = "background-image: url({$background_image_url});";
}

$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => $slide_classes,
] );
?>

<div id="slide-<?php echo esc_attr( $id ); ?>" <?php echo wp_kses_data($wrapper_attributes)?> style="<?php echo esc_attr($slide_style) ?>" tabindex="0">
<?php if ( $timeline_content ) : ?>
	<div class="slide-timelineContent">
		<div class="slide-timelineContent-inner"><?php echo wp_kses_post( $timeline_content ); ?></div>
	</div>
<?php endif; ?>

<?php if ( ! $hide_content ) : ?>
	<?php if ( $has_content ) : ?>
		<div class="slide-contentWrapper" tabindex="0">
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
<?php endif; ?>
</div>
