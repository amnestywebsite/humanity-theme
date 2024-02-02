<?php

declare( strict_types = 1 );

namespace Amnesty;

new WordPress_Seo();

/**
 * WordPress SEO (Yoast) metadata handling class.
 *
 * Registers, or modifies registration of, Yoast's meta keys
 * to allow for access via the REST API - supports migration
 * work of Amnesty.org
 *
 * @package Amnesty\RestApi
 */
class WordPress_Seo {

	/**
	 * Yoast's meta keys
	 *
	 * @var array<int,string>
	 */
	protected static array $meta_keys = [
		'_yoast_wpseo_meta-robots-nofollow',
		'_yoast_wpseo_meta-robots-noindex',
		'_yoast_wpseo_metadesc',
		'_yoast_wpseo_opengraph-description',
		'_yoast_wpseo_opengraph-image-id',
		'_yoast_wpseo_opengraph-image',
		'_yoast_wpseo_opengraph-title',
		'_yoast_wpseo_title',
		'_yoast_wpseo_twitter-description',
		'_yoast_wpseo_twitter-image-id',
		'_yoast_wpseo_twitter-image',
		'_yoast_wpseo_twitter-title',
		'_yoast_wpseo_canonical',
		'_yoast_wpseo_focuskw',
	];

	/**
	 * Bind hooks
	 */
	public function __construct() {
		// only bind if Yoast is enabled
		if ( ! defined( 'WPSEO_VERSION' ) ) {
			return;
		}

		add_action( 'init', [ $this, 'register' ], 10000 );
		add_filter( 'register_meta_args', [ $this, 'modify' ], 100, 4 );
	}

	/**
	 * Register meta if not registered
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( static::$meta_keys as $key ) {
			if ( registered_meta_key_exists( 'post', $key, 'post' ) ) {
				continue;
			}

			register_meta(
				'post',
				$key,
				[
					'object_subtype' => 'post',
					'type'           => false === strpos( $key, '-id' ) ? 'string' : 'integer',
					'single'         => true,
					'show_in_rest'   => true,
					'auth_callback'  => '__return_true',
				]
			);
		}
	}

	/**
	 * Modify meta registration
	 *
	 * @param array<string,mixed> $args        meta registration params
	 * @param array<string,mixed> $defaults    meta registration default params
	 * @param string              $object_type object type meta is being registered for
	 * @param string              $meta_key    the meta key being registered
	 *
	 * @return array
	 */
	public function modify( array $args, array $defaults, string $object_type, string $meta_key ): array {
		if ( in_array( $meta_key, static::$meta_keys, true ) ) {
			$args['show_in_rest'] = true;

			if ( ! isset( $args['auth_callback'] ) ) {
				$args['auth_callback'] = '__return_true';
			}
		}

		return $args;
	}

}
