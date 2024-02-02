<?php

declare( strict_types = 1 );

// remove yoast comments
add_filter( 'wpseo_debug_markers', '__return_false' );
