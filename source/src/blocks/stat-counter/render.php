<?php

$value   = $attributes['value'];
$options = get_option( 'amnesty_localisation_options_page' );

if ( 'on' === ( $options['enforce_grouping_separators'] ?? false ) ) {
	$value = number_format_i18n( $value );
}

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'class'         => "align{$attributes['alignment']}",
		'data-duration' => $attributes['duration'],
		'data-value'    => $value,
	]
);

?>
<div <?php echo wp_kses_data( $wrapper_attributes ); ?>><?php echo wp_kses_post( $value ); ?></div>
