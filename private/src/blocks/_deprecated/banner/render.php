<?php

// use the amnesty_render_header_block function to render the banner block
echo wp_kses_post( \Amnesty\Blocks\amnesty_render_header_block( $attributes, $content ) );
