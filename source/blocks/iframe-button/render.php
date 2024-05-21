<?php

if ( ! esc_url( $attributes['iframeUrl'] ) ) {
	return;
}

$alignment = '';

if ( 'none' !== $attributes['alignment'] ) {
	$alignment = sprintf( 'is-%s-aligned', $attributes['alignment'] );
}

?>
<div class="iframeButton-wrapper <?php echo esc_attr( $alignment ); ?>">
	<button class="iframeButton btn <?php echo esc_attr( $attributes['className'] ); ?>">
		<?php echo esc_html( $attributes['buttonText'] ); ?>
	</button>
	<iframe frameborder="0" src="<?php echo esc_url( $attributes['iframeUrl'] ); ?>" title="<?php echo esc_attr( $attributes['title'] ); ?>" height="<?php echo esc_attr( $attributes['iframeHeight'] ); ?>"></iframe>
</div>
