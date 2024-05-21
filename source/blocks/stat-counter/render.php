<?php

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'class'         => 'align' . $attributes['alignment'],
		'data-duration' => $attributes['duration'],
		'data-value'    => $attributes['value'],
	]
);

?>
<div <?php echo wp_kses_data( $wrapper_attributes ); ?>><?php echo wp_kses_post( $attributes['value'] ); ?></div>
