<?php

if ( 'wide' === $attributes['style'] ) {
	require realpath( __DIR__ . '/render-wide.php' );
} else {
	require realpath( __DIR__ . '/render-standard.php' );
}
