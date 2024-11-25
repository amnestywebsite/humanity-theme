<?php

if ( !isset( $attributes['ctaLink'] ) || !isset( $attributes['ctaText'] ) ) {
	return;
}

 $linkClasses = sprintf(
	'btn btn--%s',
	$attributes['style']
);

$wrapperAttributes = get_block_wrapper_attributes(
	[
		'class' =>  $linkClasses,
	]
);

?>

<a <?php echo wp_kses_data( $wrapperAttributes ); ?> href="<?php echo esc_url( $attributes['ctaLink'] ); ?>">
	<span><?php echo esc_html( $attributes['ctaText'] ); ?></span>
</a>
