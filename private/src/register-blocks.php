<?php

declare( strict_types = 1 );

namespace Amnesty;

use DirectoryIterator;
use IteratorIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use function add_action;
use function generate_block_asset_handle;
use function register_block_type;
use function wp_cache_get;
use function wp_cache_set;
use function wp_is_development_mode;
use function wp_localize_script;

/**
 * https://gist.github.com/jaymcp/c243340b54cc565dd78b85ca59cbdd50
 */
new class() {

	/**
	 * List of found blocks
	 *
	 * @var array<string,array{'block.json'?:string,'autoload':array<int,string>,'localisation':array<int,string>}>
	 */
	protected array $blocks = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );

		foreach ( [ 'admin', 'editor', 'editor-plugins', 'frontend', 'shared' ] as $slug ) {
			$file = __DIR__ . DIRECTORY_SEPARATOR . 'register-' . $slug . '-assets.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}

	/**
	 * Initialise.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->blocks = wp_cache_get( 'block-list', __NAMESPACE__ ) ?: [];

		if ( wp_is_development_mode( 'plugin' ) || 0 === count( $this->blocks ) ) {
			$this->find_blocks();
		}

		$this->load_blocks();
		$this->load_block_styles();
	}

	/**
	 * Find all blocks defined by the iterator
	 *
	 * @return void
	 */
	protected function find_blocks(): void {
		$ds = DIRECTORY_SEPARATOR;

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				__DIR__ . $ds . 'blocks',
			),
		);

		$blocks = [];

		/**
		 * Each entry is an object instance
		 *
		 * @var \SplFileInfo $entry
		 */
		foreach ( $iterator as $entry ) {
			if ( ! $entry->isFile() ) {
				continue;
			}

			$dirname  = basename( dirname( $entry->getRealPath() ) );
			$basename = $entry->getBasename();

			// some blocks load their HTML from within a views dir
			if ( 'views' === $dirname ) {
				continue;
			}

			if ( ! array_key_exists( $dirname, $blocks ) ) {
				$blocks[ $dirname ] = [
					'autoload'     => [],
					'localisation' => [],
				];
			}

			if ( 'block.json' === $basename ) {
				$blocks[ $dirname ]['block.json'] = $entry->getRealPath();
				continue;
			}

			// we only care about php files here
			if ( 'php' !== $entry->getExtension() ) {
				continue;
			}

			// we don't need to do anything with asset data
			if ( str_ends_with( $basename, '.asset.php' ) ) {
				continue;
			}

			// renderers are included automagically
			if ( str_starts_with( $basename, 'render' ) ) {
				continue;
			}

			if ( str_starts_with( $basename, 'localise' ) ) {
				$blocks[ $dirname ]['localisation'][] = $entry->getRealPath();
				continue;
			}

			$blocks[ $dirname ]['autoload'][] = $entry->getRealPath();
		}

		ksort( $blocks );

		wp_cache_set( 'block-list', $blocks, __NAMESPACE__ );

		$this->blocks = $blocks;
	}

	/**
	 * Register blocks, autoload, and localise their assets
	 *
	 * @return void
	 */
	protected function load_blocks(): void {
		foreach ( $this->blocks as $dirname => $block ) {
			// block is invalid
			if ( ! array_key_exists( 'block.json', $block ) ) {
				continue;
			}

			// load its required files
			foreach ( $block['autoload'] as $file ) {
				require_once $file;
			}

			// register the block
			register_block_type_from_metadata( $block['block.json'] );

			// localise block assets
			$this->localise( $dirname, $block['localisation'] );
		}
	}

	/**
	 * Load block style registrations
	 *
	 * @return void
	 */
	protected function load_block_styles(): void {
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

	/**
	 * Perform asset localisation
	 *
	 * @param string            $block The block name
	 * @param array<int,string> $files The localisation files
	 *
	 * @return void
	 */
	protected function localise( string $block, array $files ): void {
		foreach ( $files as $file ) {
			$field_name   = $this->camel( preg_replace( '/^localise-(.*)\.php$/', '$1', basename( $file ) ) );
			$asset_handle = generate_block_asset_handle( 'amnesty-core/' . $block, $field_name );
			$file_data    = require $file;

			foreach ( $file_data as $object_name => $data ) {
				wp_localize_script( $asset_handle, $object_name, $data );
			}
		}
	}

	/**
	 * Convert a string to PascalCase
	 *
	 * @param string $the_string the string to texturise
	 *
	 * @return string
	 */
	protected function pascal( string $the_string = '' ): string {
		$the_string = preg_replace( '/[\'"]/', '', $the_string );
		$the_string = preg_replace( '/[^a-zA-Z0-9]+/', ' ', $the_string );
		return preg_replace( '/\s+/', '', ucwords( $the_string ) );
	}

	/**
	 * Convert a string to camelCase
	 *
	 * @param string $the_string the string to texturise
	 *
	 * @return string
	 */
	protected function camel( string $the_string = '' ): string {
		return lcfirst( $this->pascal( $the_string ) );
	}
};
