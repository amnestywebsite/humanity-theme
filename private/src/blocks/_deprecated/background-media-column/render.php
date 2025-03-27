<?php

$classes = classnames(
	'wp-block-cover',
	'is-light',
	'text-media--itemContainer',
	[
		"align{$attributes['horizontalAlignment']}"        => (bool) $attributes['horizontalAlignment'],
		"is-vertically-aligned-{$attributes['verticalAlignment']}" => (bool) $attributes['verticalAlignment'],
		"has-{$attributes['background']}-background-color" => (bool) $attributes['background'],
	]
);

if ( 0 === absint( $attributes['image'] ) ) :

	?>

	<div id="<?php echo esc_attr( $attributes['uniqId'] ); ?>" class="<?php echo esc_attr( $classes ); ?>">
		<?php echo wp_kses_post( $content ); ?>
	</div>

	<?php

	return;
endif;


$x_offset = round( floatval( $attributes['focalPoint']['x'] ) * 100, 2 );
$y_offset = round( floatval( $attributes['focalPoint']['y'] ) * 100, 2 );
$opacity  = round( floatval( $attributes['opacity'] ), 2 );

$image = wp_get_attachment_image(
	absint( $attributes['image'] ),
	'post-thumbnail',
	attr: [
		'class' => 'wp-block-cover__image-background wp-image-' . $attributes['image'],
		'style' => 'background-position:' . $x_offset . '% ' . $y_offset . '%;opacity:' . $opacity,
	],
);

?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo wp_kses_post( $image ); ?>
	<div class="wp-block-cover__inner-container"><?php echo wp_kses_post( $content ); ?></div>
</div>
