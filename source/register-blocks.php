<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_fse_blocks' ) ) {
	/**
	 * Register FSE blocks
	 *
	 * @return void
	 */
	function amnesty_register_fse_blocks(): void {
		$basedir  = get_template_directory() . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $basedir ) );

		foreach ( $iterator as $entry ) {
			// directory contains block.json - is a block path
			if ( $entry->isFile() && 'block' === $entry->getBasename( '.json' ) ) {
				register_block_type( $entry->getRealPath() );
			}

			if ( $entry->isFile() && 'localisation.php' === $entry->getBasename() ) {
				require_once $entry->getRealPath();
			}
		}
	}
}

add_action( 'init', 'amnesty_register_fse_blocks' );