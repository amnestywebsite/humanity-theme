<?php

declare( strict_types = 1 );

add_action( 'init', 'humanity_register_blocks' );

if ( ! function_exists( 'humanity_register_blocks' ) ) {
	/**
	 * Register FSE blocks
	 *
	 * @return void
	 */
	function humanity_register_blocks(): void {
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

add_action( 'init', 'humanity_register_block_styles' );

if ( ! function_exists( 'humanity_register_block_styles' ) ) {
	/**
	 * Load block style registrations
	 *
	 * @return void
	 */
	function humanity_register_block_styles(): void {
		if ( ! is_dir( __DIR__ . DIRECTORY_SEPARATOR . 'block-styles' ) ) {
			return;
		}

		$iterator = new IteratorIterator( new DirectoryIterator( __DIR__ . DIRECTORY_SEPARATOR . 'block-styles' ) );

		/**
		 * Each entry is an object instance
		 *
		 * @var \SplFileInfo $entry
		 */
		foreach ( $iterator as $entry ) {
			if ( $entry->isFile() && 'php' === $entry->getExtension() ) {
				require_once $entry->getPathname();
			}
		}
	}
}

( function () {
	foreach ( [ 'admin', 'editor', 'editor-plugins', 'frontend' ] as $slug ) {
		if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'register-' . $slug . '-assets.php' ) ) {
			require_once __DIR__ . DIRECTORY_SEPARATOR . 'register-' . $slug . '-assets.php';
		}
	}
} )();
