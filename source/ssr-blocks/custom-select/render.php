<?php

// if there are no options, or there's only one and it has no value (i.e. placeholder), don't render at all
if ( empty( $attributes['options'] ) || ( 1 === count( $attributes['options'] ) && ! array_keys( $attributes['options'] )[0] ) ) {
	return;
}

if ( ! $attributes['isForm'] ) {
	unset( $attributes['type'] );
}

if ( ! empty( $attributes['type'] ) && ! in_array( $attributes['type'], [ 'nav', 'filter' ], true ) ) {
	$attributes['type'] = 'filter';
}

$make_id = fn ( string $value ): string => amnesty_hash_id( $attributes['name'] . '-' . $value );

if ( true === $attributes['multiple'] ) {
	require __DIR__ . 'render-multiselect.php';
	return;
}

if ( true === $attributes['isForm'] ) {
	require __DIR__ . 'render-form.php';
	return;
}

if ( true === $attributes['isNav'] ) {
	require __DIR__ . 'render-nav.php';
	return;
}

require __DIR__ . 'render-control.php';
