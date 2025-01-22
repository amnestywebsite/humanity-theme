<?php

$classes = classnames(
	'linksWithIcons-group',
	sprintf( 'is-%s', $attributes['orientation'] ),
	sprintf( 'has-%s-items', $attributes['quantity'] ),
	$attributes['className'],
	[
		'has-background' => $attributes['backgroundColor'],
		'has-no-lines'   => $attributes['hideLines'],
	],
	[
		sprintf( 'has-%s-background-color', $attributes['backgroundColor'] ) => $attributes['backgroundColor'],
		sprintf( 'icon-%s', $attributes['dividerIcon'] ) => $attributes['dividerIcon'],
	]
);

?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo wp_kses_post( $content ); ?>
</div>

