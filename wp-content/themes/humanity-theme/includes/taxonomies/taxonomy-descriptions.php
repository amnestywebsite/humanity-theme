<?php

declare( strict_types = 1 );

// allow HTML in term descriptions
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );
add_filter( 'term_description', 'wptexturize' );
add_filter( 'term_description', 'convert_chars' );
add_filter( 'term_description', 'wpautop' );
add_filter( 'term_description', 'wp_kses_post' );
