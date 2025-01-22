<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_fse_blocks' ) ) {
	/**
	 * Register FSE blocks
	 *
	 * @return void
	 */
	function amnesty_register_fse_blocks(): void {
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( __DIR__ . DIRECTORY_SEPARATOR . 'blocks' ) );

		/**
		 * Each entry is an object instance
		 *
		 * @var \SplFileInfo $entry
		 */
		foreach ( $iterator as $entry ) {
			// directory contains block.json - is a block path
			if ( $entry->isFile() && 'block' === $entry->getBasename( '.json' ) ) {
				register_block_type( $entry->getRealPath() );
			}

			// require PHP files that support a block (exclude render!)
			if ( $entry->isFile() && 'php' === $entry->getExtension() && ! str_starts_with( $entry->getBasename(), 'render' ) ) {
				require_once $entry->getRealPath();
			}
		}
	}
}

add_action( 'init', 'amnesty_register_fse_blocks' );
