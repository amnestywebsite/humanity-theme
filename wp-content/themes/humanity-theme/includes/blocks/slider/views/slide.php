<?php

$slide_classes = classnames(
	'slide',
	[
		"is-{$alignment}-aligned"      => $alignment,
		"has-{$background}-background" => $background,
	] 
);

?>
<div id="slide-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $slide_classes ); ?>" tabindex="0">
<?php if ( $timeline_content ) : ?>
	<div class="slide-timelineContent">
		<div class="slide-timelineContent-inner"><?php echo wp_kses_post( $timeline_content ); ?></div>
	</div>
<?php endif; ?>

<?php if ( ! $hide_content && $has_content ) : ?>
	<div class="slide-contentWrapper" data-tooltip="<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ AM not seen this in use, might be to close a gallery */ esc_attr_e( 'Tap here to return to gallery', 'amnesty' ); ?>">
		<div class="slide-contentContainer">
		<?php if ( $title ) : ?>
			<h1 class="slide-title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php endif; ?>

		<?php if ( $subtitle ) : ?>
			<h2 class="slide-subtitle"><?php echo wp_kses_post( $subtitle ); ?></h2>
		<?php endif; ?>

		<?php if ( $has_inner ) : ?>
			<div class="slide-content">
			<?php if ( $content ) : ?>
				<div><?php echo wp_kses_post( $content ); ?></div>
			<?php endif; ?>

			<?php if ( $show_cta_btn ) : ?>
				<a class="btn" href="<?php echo esc_url( $cta_link ); ?>"><?php echo wp_kses_post( $cta_text ); ?></a>
			<?php endif; ?>

			<?php if ( $show_toggle ) : ?>
				<button class="slider-toggleContent"><?php /* translators: [front] */ esc_html_e( 'Toggle Content', 'amnesty' ); ?></button>
			<?php endif; ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
</div>
