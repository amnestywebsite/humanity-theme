<?php

$classes = classnames(
	'factBlock',
	[
		'has-background' => (bool) $attrs['background'],
	],
	[
		"has-{$attrs['background']}-background-color" => (bool) $attrs['background'],
	],
);

$label = sanitize_title_with_dashes( $attrs['title'] );

?>
<aside class="<?php echo esc_attr( $classes ); ?>" aria-labelledby="<?php echo esc_attr( $label ); ?>">
	<h2 id="<?php echo esc_attr( $label ); ?>" class="factBlock-title" aria-hidden="true"><?php echo wp_kses_post( $attrs['title'] ); ?></h2>
	<ol><?php echo wp_kses_post( $content ); ?></ol>
</aside>
