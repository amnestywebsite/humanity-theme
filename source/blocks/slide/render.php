<?php

$data = wp_parse_args(
	$data,
	[
		'alignment'        => '',
		'background'       => '',
		'callToActionLink' => '',
		'callToActionText' => '',
		'content'          => '',
		'heading'          => '',
		'hideContent'      => '',
		'id'               => '',
		'imageId'          => 0,
		'subheading'       => '',
		'timelineContent'  => '',
	]
);

$show_cta_btn = (bool) $attrs['callToActionText'] && (bool) $attrs['callToActionLink'];
$show_toggle  = $show_cta_btn || (bool) $attrs['content'];
$has_inner    = $show_cta_btn || $show_toggle;

$slide_classes = classnames(
	'slide',
	$attrs['className'],
	[
		"has-{$attrs['background']}-background" => $attrs['background'],
	]
);

?>
<div id="slide-<?php echo esc_attr( $attrs['id'] ); ?>" class="<?php echo esc_attr( $slide_classes ); ?>" tabindex="0">
<?php if ( (bool) $attrs['timelineContent'] ) : ?>
	<div class="slide-timelineContent">
		<div class="slide-timelineContent-inner"><?php echo wp_kses_post( $attrs['timelineContent'] ); ?></div>
	</div>
<?php endif; ?>

<?php if ( ! $attrs['hideContent'] && $block->context['amnesty-core/slider/hasContent'] ) : ?>
	<div class="slide-contentWrapper" data-tooltip="<?php /* translators: [front] https://wordpresstheme.amnesty.org/blocks/b006-timeline-slider/ AM not seen this in use, might be to close a gallery */ esc_attr_e( 'Tap here to return to gallery', 'amnesty' ); ?>">
		<div class="slide-contentContainer">
		<?php if ( $attrs['heading'] ) : ?>
			<h1 class="slide-title"><?php echo wp_kses_post( $attrs['heading'] ); ?></h1>
		<?php endif; ?>

		<?php if ( $attrs['subheading'] ) : ?>
			<h2 class="slide-subtitle"><?php echo wp_kses_post( $attrs['subheading'] ); ?></h2>
		<?php endif; ?>

		<?php if ( $has_inner ) : ?>
			<div class="slide-content">
			<?php if ( $attrs['content'] ) : ?>
				<div><?php echo wp_kses_post( $attrs['content'] ); ?></div>
			<?php endif; ?>

			<?php if ( $show_cta_btn ) : ?>
				<a class="btn" href="<?php echo esc_url( $attrs['callToActionLink'] ); ?>"><?php echo wp_kses_post( $attrs['callToActionText'] ); ?></a>
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
