<?php

$classes = classnames(
	'blockquote',
	[
		"align-{$attributes['align']}" => (bool) $attributes['align'],
		"is-{$attributes['size']}"     => (bool) $attributes['size'],
		"is-{$attributes['colour']}"   => (bool) $attributes['colour'],
		'is-capitalised'               => (bool) $attributes['capitalise'],
		'is-lined'                     => (bool) $attributes['lined'],
	]
);

?>

<blockquote class="<?php echo esc_attr( $classes ); ?>">
<?php if ( $attributes['content'] ) : ?>
	<?php echo wp_kses_post( wpautop( $attributes['content'] ) ); ?>
<?php endif; ?>
<?php if ( $attributes['citation'] ) : ?>
	<cite><?php echo esc_html( wp_strip_all_tags( $attributes['citation'] ) ); ?></cite>
<?php endif; ?>
</blockquote>
