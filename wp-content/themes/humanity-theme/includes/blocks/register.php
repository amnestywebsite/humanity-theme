<?php

declare( strict_types = 1 );

add_filter( 'big_bite_block_tabbed_content_show_tab_id_settings', '__return_true' );

if ( ! function_exists( 'register_block_type' ) ) {
	return;
}

require_once __DIR__ . '/_deprecated/header/class-header-block-renderer.php';
require_once __DIR__ . '/_deprecated/header/register.php';
require_once __DIR__ . '/hero/helpers.php';
require_once __DIR__ . '/hero/render.php';
