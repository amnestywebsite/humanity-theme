<?php

if ( ! isset( $attributes['ctaLink'], $attributes['ctaText'] ) ) {
	return;
}

?>

<a <?php echo wp_kses_data( get_block_wrapper_attributes( [ 'class' => sprintf( 'btn btn--%s', $attributes['style'] ) ] ) ); ?> href="<?php echo esc_url( $attributes['ctaLink'] ); ?>">
	<span><?php echo esc_html( $attributes['ctaText'] ); ?></span>
</a>
