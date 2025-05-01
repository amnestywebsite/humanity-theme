<?php

if ( ! esc_url( $attributes['iframeUrl'] ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => 'iframeButton-wrapper' ] );

?>
<div <?php echo wp_kses_data( $wrapper_attributes ); ?>>
	<button class="iframeButton btn <?php echo esc_attr( $attributes['className'] ?? '' ); ?>">
		<?php echo esc_html( $attributes['buttonText'] ); ?>
	</button>
	<iframe frameborder="0" src="<?php echo esc_url( $attributes['iframeUrl'] ); ?>" title="<?php echo esc_attr( $attributes['title'] ); ?>" height="<?php echo esc_attr( $attributes['iframeHeight'] ); ?>"></iframe>
</div>
