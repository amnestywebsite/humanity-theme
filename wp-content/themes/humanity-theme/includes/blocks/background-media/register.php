<?php

declare( strict_types = 1 );

if ( !function_exists('register_background_media_block') ) {
	function register_background_media_block(): void {
		register_block_type(
			'amnesty-core/background-media',
			[
				'render_callback' => 'render_background_media_block',
				'editor_script'   => 'amnesty-core-blocks-js',
			]
		);
	}
}
