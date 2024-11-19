<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_localise_sidebar' ) ) {
	/**
	 * Add script localisation for the sidebar block
	 *
	 * @return void
	 */
	function amnesty_localise_sidebar(): void {
		wp_localize_script(
			'amnesty-core-sidebar-editor-script',
			'aiSidebar',
			[
				'defaultSidebars' => [
					'post'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ),
					'page'        => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar_page' ) ),
					'wp_template' => array_map( 'absint', (array) amnesty_get_option( '_default_sidebar' ) ), // for the site editor
				],
			],
		);
	}
}

add_action( 'enqueue_block_assets', 'amnesty_localise_sidebar' );
