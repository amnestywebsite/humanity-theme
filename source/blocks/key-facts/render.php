<?php


$classes = classnames(
	'factBlock',
	[
		'has-background' => (bool) $attributes['background'],
	],
	[
		"has-{$attributes['background']}-background-color" => (bool) $attributes['background'],
	],
);

$wrapper_attibutes = get_block_wrapper_attributes(
	[
		'class' => $classes,
	],
);

$label = sanitize_title_with_dashes( $attributes['title'] );

?>
<aside class="<?php echo esc_attr( $classes ); ?>" aria-labelledby="<?php echo esc_attr( $label ); ?>">
	<h2 id="<?php echo esc_attr( $label ); ?>" class="factBlock-title" aria-hidden="true"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
	<ol><?php echo $content ?></ol>
</aside>
