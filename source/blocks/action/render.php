<?php

spaceless();

if ( 'wide' === $attributes['style'] ) {
	require realpath( __DIR__ . '/wide.php' );
	return endspaceless( false );
}

require realpath( __DIR__ . '/standard.php' );
return endspaceless( false );
