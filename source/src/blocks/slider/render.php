<?php

$slider_id   = $attributes['sliderId'] ?? '';
$quantity    = $attributes['quantity'] ?? 1;
$has_arrows  = $attributes['hasArrows'] ?? true;
$show_tabs   = $attributes['showTabs'] ?? true;
$has_content = $attributes['hasContent'] ?? false;
$style       = $attributes['style'] ?? 'dark';
$title       = $attributes['title'] ?? '';

$slider_classes = classnames(
	'slider',
	[
		'has-arrows'     => $has_arrows,
		'show-tabs'      => $show_tabs,
		'has-content'    => $has_content,
		"style-{$style}" => $style,
	]
);

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $slider_classes ] );

$blocks = parse_blocks( get_post_field( 'post_content' ) );

$slide_blocks = '';

foreach ( $blocks as $block ) {
	if ( 'amnesty-core/block-slider' === $block['blockName'] ) {
		$slide_blocks = $block['innerBlocks'];
	}
}

$slide_titles = [];

foreach ( $slide_blocks as $slide ) {
	array_push( $slide_titles, $slide['attrs']['title'] );
}

$buttons = '';

foreach ( $slide_titles as $index => $slide_title ) {
	$buttons .= '<button class="slider-navButton" aria-label="Go to slide: ' . htmlspecialchars($slide_title) . '">' . htmlspecialchars($slide_title) . '</button>';
}

// If there are fewer buttons than the $quantity, create additional buttons (blank or default)
for ( $i = count( $slide_titles ); $i < $quantity; $i++ ) {
	// Optionally, you could make this button blank or set a default aria-label if needed
	$buttons .= '<button class="slider-navButton" aria-label="Go to slide"></button>';
}

?>

<div id="slider-<?php echo esc_attr( $slider_id ); ?>" <?php echo wp_kses_data( $wrapper_attributes ); ?>>
<?php if ( $title ) : ?>
	<h2 class="slider-title"><?php echo wp_kses_post( $title ); ?></h2>
<?php endif; ?>

	<div class="slides-container">
		<div class="slides">
			<?php echo wp_kses_post( $content ); ?>
		</div>
		<?php if ( $has_arrows ) : ?>
			<button class="slides-arrow slides-arrow--previous" aria-label="<?php esc_attr_e( 'Previous slide', 'amnesty' ); ?>">
				<?php echo file_get_contents( get_template_directory() . '/assets/svg/arrow-left.svg' ); ?>
			</button>
			<button class="slides-arrow slides-arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'amnesty' ); ?>">
				<?php echo file_get_contents( get_template_directory() . '/assets/svg/arrow-right.svg' ); ?>
			</button>
		<?php endif; ?>
		<?php if ( $show_tabs ) : ?>
			<div class="slider-navContainer">
				<div class="slider-nav">
					<?php echo wp_kses_post( $buttons ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
