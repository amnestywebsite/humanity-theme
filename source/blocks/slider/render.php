<?php

echo '<pre>';
var_dump($attributes);
echo '</pre>';

$slider_id = $attributes['sliderId'] ?? '';
$quantity = $attributes['quantity'] ?? 1;
$has_arrows = $attributes['hasArrows'] ?? true;
$show_tabs = $attributes['showTabs'] ?? true;
$has_content = $attributes['hasContent'] ?? false;
$style = $attributes['style'] ?? 'dark';
$title = $attributes['title'] ?? '';

$slider_classes = classnames(
	'slider',
	[
		'has-arrows' => $has_arrows,
		'show-tabs' => $show_tabs,
		'has-content' => $has_content,
		"style-{$style}" => $style,
	]
);

?>

<div id="slider-<?php echo esc_attr( $slider_id ); ?>" class="<?php echo esc_attr( $slider_classes ); ?>">
	<?php if ( $title ) : ?>
		<h2 class="slider-title"><?php echo wp_kses_post( $title ); ?></h2>
	<?php endif; ?>

	<div class="slides-container">
		<div class="slides">
			<?php echo $content; ?>
		</div>
		<button class="slides-arrow slides-arrow--previous" aria-label="<?php esc_attr_e( 'Previous slide', 'amnesty' ); ?>">
			<?php echo file_get_contents( get_template_directory() . '/assets/svg/arrow-left.svg' ); ?>
		</button>
		<button class="slides-arrow slides-arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'amnesty' ); ?>">
			<?php echo file_get_contents( get_template_directory() . '/assets/svg/arrow-right.svg' ); ?>
		</button>
		<div class="slider-navContainer">
			<div class="slider-nav">
				<?php for ( $i = 0; $i < $quantity; $i++ ) : ?>
					<button class="slider-navButton" aria-label="<?php esc_attr_e( 'Go to slide', 'amnesty' ); ?>">

					</button>
				<?php endfor; ?>
			</div>
	</div>
