<?php

if ( 'standard-menu' === $attributes['type'] ) {
	if ( ! $attributes['menuId'] ) {
		return;
	}

	require __DIR__ . '/render-wp-nav.php';
	return;
}

if ( 'inpage-menu' === $attributes['type'] ) {
	require __DIR__ . '/render-in-page.php';
	return;
}

if ( 'custom-menu' === $attributes['type'] && ! empty( $attributes['items'] ) ) {
	require __DIR__ . '/render-custom.php';
	return;
}
