<?php

	if ( false !== strpos( $content, 'class="callToAction' ) ) {
		return $content;
	}

	$pre_heading = $attributes['preheading'];
	$heading     = $attributes['title'];
	$cta_content = $attributes['content'];

	// Set the classes
	$classes = classnames(
		'callToAction',
		[
			"callToAction--{$attributes['background']}" => (bool) $attributes['background'],
		]
	);

?>

<div class="<?php echo esc_attr( $classes ) ?>" role="note" aria-label="<?php echo esc_attr( $heading ) ?>">
	<h2 class="callToAction-preHeading"><?php echo wp_kses_post( $pre_heading ) ?></h2>
	<h1 class="callToAction-heading"><?php echo wp_kses_post( $heading ) ?></h1>
	<p class="callToAction-content"><?php echo wp_kses_post( $cta_content ) ?></p>
	<div className="innerBlocksContainer">
		<?php echo wp_kses_post( $content ) ?>
	</div>
</div>
