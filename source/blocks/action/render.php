<?php

spaceless();

if ( 'wide' === $attributes['style'] ) {
	require realpath( __DIR__ . '/render-wide.php' );
	return endspaceless( false );
}

require realpath( __DIR__ . '/render-standard.php' );
return endspaceless( false );
