<?php

declare( strict_types = 1 );

namespace Amnesty;

use Closure;
use WP_Error;
use WP_Post;
use WP_Taxonomy;
use WP_Term_Query;
use WpeCommon;

new Caching();

/**
 * Cache modifications/hooks
 */
class Caching {

	/**
	 * Post types that don't have pages to cache
	 *
	 * @var array<int,string>
	 */
	protected static array $nocache_post_types = [
		'nav_menu',
		'nav_menu_item',
		'revision',
		'wp_template',
		'wp_template_part',
	];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'pre_post_update', [ $this, 'setup' ], 10, 2 );
		add_action( 'delete_post', [ $this, 'setup' ], 10, 2 );
	}

	/**
	 * Bind additionl parameter(s) to a callback
	 *
	 * @param callable $callback the callback to bind
	 * @param mixed    ...$extra the extra parameter(s)
	 *
	 * @return Closure
	 */
	public function bind_param( callable $callback, ...$extra ): Closure {
		return fn ( ...$params ) => call_user_func_array( $callback, [ ...$params, ...$extra ] );
	}

	/**
	 * Bind action hooks
	 *
	 * @param int           $post_id the post ID
	 * @param WP_Post|array $post    the post object
	 *
	 * @return void
	 */
	public function setup( $post_id, $post ): void {
		if ( is_array( $post ) ) {
			$post = (object) ( $post + [ 'ID' => $post_id ] );
		}

		$permalink = get_permalink( $post );

		// we need the permalink to determine the path to purge
		if ( ! $permalink ) {
			return;
		}

		add_action(
			'clean_post_cache',
			$this->bind_param(
				[ $this, 'clean_post_cache' ],
				$post,
				$permalink,
			),
		);
	}

	/**
	 * Purge the Varnish cache for an item
	 *
	 * @param int    $post_id   the item to purge
	 * @param object $post      the post object
	 * @param string $permalink the item's permalink
	 *
	 * @return void
	 */
	public function clean_post_cache( int $post_id, object $post, string $permalink ): void {
		if ( $post_id !== $post->ID || ! class_exists( '\\WpeCommon' ) ) {
			return;
		}

		$this->purge_varnish( $post_id, false, $post, $permalink );
	}

	/**
	 * Reimplementation of WP Engine's Varnish cache purging,
	 * because theirs explicitly doesn't purge attachment pages,
	 * nor does it correctly generate other paths to purge (term index pages, etc).
	 *
	 * @see \WpeCommon::purge_varnish_cache()
	 *
	 * @param int|null $post_id   ID for the object being purged (null for global)
	 * @param bool     $force     whether to force a purge
	 * @param object   $post      the post object
	 * @param string   $permalink the post permalink
	 *
	 * @return bool
	 */
	public function purge_varnish( ?int $post_id, bool $force, object $post, string $permalink ): bool {
		static $purge_counter;

		if ( ! $this->is_valid_for_purge() ) {
			return false;
		}

		if ( isset( $purge_counter ) && $purge_counter > 2 && ! $force ) {
			return false;
		}

		// If post id is null, purge everything
		if ( null === $post_id ) {
			return $this->send_purge_requests( null, null, [] );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			return false;
		}

		if ( is_array( $post ) ) {
			$post = (object) $post;
		}

		$paths = $this->get_purge_paths( $post_id, $post, $permalink );

		if ( ! count( $paths ) ) {
			return false;
		}

		// Safeguard against sending too many purge requests if more than one post update happens in a single request
		if ( ! isset( $purge_counter ) ) {
			$purge_counter = 0;
		}

		++$purge_counter;

		return $this->send_purge_requests( $post_id, $post, $paths );
	}

	/**
	 * Check whether the current request is valid for cache purging
	 *
	 * @return bool
	 */
	protected function is_valid_for_purge(): bool {
		// Cache purging is globally disallowed
		if ( defined( 'WPE_DISABLE_CACHE_PURGING' ) && \WPE_DISABLE_CACHE_PURGING ) {
			return false;
		}

		// Autosaves are not cacheable
		if ( defined( 'DOING_AUTOSAVE' ) && \DOING_AUTOSAVE ) {
			return false;
		}

		// Not a production domain
		if ( function_exists( 'is_wpe_snapshot' ) && is_wpe_snapshot() ) {
			return false;
		}

		return true;
	}

	/**
	 * Build list of domains to purge against
	 *
	 * @param int|null    $post_id the ID of the post being purged
	 * @param object|null $post    the post object
	 *
	 * @return array<int,string>
	 */
	protected function get_purge_domains( ?int $post_id = null, ?object $post = null ): array {
		global $wpdb, $wpengine_platform_config;

		$domain_list = [];
		if ( isset( $wpengine_platform_config['all_domains'] ) ) {
			$domain_list = $wpengine_platform_config['all_domains'];
		}

		$purge_domains = [];

		// Item isn't workable - purge everything. (Seems heavyhanded...)
		if ( ! $post_id || ! (bool) $post ) {
			$purge_domains = $domain_list;

			if ( isset( $wpdb->dmtable ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$domains = (array) $wpdb->get_col( "SELECT domain FROM {$wpdb->dmtable}" );
				$domains = array_map( 'strtolower', $domains );

				$purge_domains = array_merge( $purge_domains, $domains );
			}
		} else {
			$blog_url       = home_url();
			$blog_url_parts = wp_parse_url( $blog_url );
			$blog_domain    = $blog_url_parts['host'];
			$purge_domains  = [ $blog_domain ];
		}

		$purge_domains = array_values( array_unique( $purge_domains ) );
		$purge_domains = array_map( 'preg_quote', $purge_domains );

		return $purge_domains;
	}

	/**
	 * Build list of paths to purge for a post
	 *
	 * @param int    $post_id   ID of the post being purged
	 * @param object $post      the post object
	 * @param string $permalink the post permalink
	 *
	 * @return array<int,string>
	 */
	protected function get_purge_paths( int $post_id, object $post, string $permalink ): array {
		if ( in_array( $post->post_type, static::$nocache_post_types, true ) ) {
			return [];
		}

		// If it's not published, it's not cached, apparently
		if ( 'attachment' !== $post->post_type && 'publish' !== $post->post_status ) {
			return [];
		}

		$perma_parts = wp_parse_url( $permalink );
		$post_path   = untrailingslashit( $perma_parts['path'] );

		if ( isset( $perma_parts['query'] ) ) {
			$post_path .= '?' . $perma_parts['query'];
		}

		$paths = [
			( preg_quote( $post_path, '/' ) . '.*' ),

			...$this->get_rest_api_purge_paths( $post ),
			...$this->get_term_index_purge_paths( $post ),
			...$this->get_blog_index_purge_paths( $post ),
		];

		$paths = array_values( array_unique( $paths ) );

		/**
		 * The paths to purge in Varnish.
		 *
		 * @param array $paths   The paths to be purged.
		 * @param int   $post_id The requested post_id to purge if one was passed.
		 */
		$paths = apply_filters( 'wpe_purge_varnish_cache_paths', $paths, $post_id );

		return (array) $paths;
	}

	/**
	 * Determine path(s) for REST API purging
	 *
	 * @param object $post the post being purged
	 *
	 * @return array<int,string>
	 */
	protected function get_rest_api_purge_paths( object $post ): array {
		/**
		 * Registered post type objects
		 *
		 * @var \WP_Post_Type[] $post_types
		 */
		$post_types = get_post_types( output: 'objects' );

		if ( ! isset( $post_types[ $post->post_type ] ) || $post_types[ $post->post_type ]->show_in_rest ) {
			return [];
		}

		$rest_path  = '/' . rest_get_url_prefix() . '/' . $post_types[ $post->post_type ]->rest_namespace;
		$rest_path .= '/' . ( $post_types[ $post->post_type ]->rest_base ?: $post->post_type );

		return [
			preg_quote( $rest_path, '/' ),
			preg_quote( $rest_path . '/' . $post->ID, '/' ) . '.*',
		];
	}

	/**
	 * Determine path(s) for term index purging
	 *
	 * @param object $post the post being purged
	 *
	 * @return array<int,string>
	 */
	protected function get_term_index_purge_paths( object $post ): array {
		if ( ! defined( 'WPE_PURGE_CATS_ON_PUB' ) || ! \WPE_PURGE_CATS_ON_PUB ) {
			return [];
		}

		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		$rest_taxes = array_filter( $taxonomies, fn ( WP_Taxonomy $tax ): bool => $tax->show_in_rest );

		$term_query = new WP_Term_Query(
			[
				'taxonomy'   => array_keys( $taxonomies ),
				'object_ids' => [ $post->ID ],
				'fields'     => 'all',
			],
		);

		$terms = (array) $term_query->get_terms();
		$paths = [];

		foreach ( $terms as $term_obj ) {
			$link = get_term_link( $term_obj->term_id );
			if ( ! is_a( $link, WP_Error::class ) ) {
				$path    = wp_parse_url( $link, PHP_URL_PATH );
				$path    = preg_quote( $path, '/' );
				$paths[] = untrailingslashit( $path );
			}

			if ( ! isset( $rest_taxes[ $term_obj->taxonomy ] ) ) {
				continue;
			}

			$tax_obj = $rest_taxes[ $term_obj->taxonomy ];

			$rest_path  = rest_get_url_prefix() . '/' . $tax_obj->rest_namespace;
			$rest_path .= '/' . ( $tax_obj->rest_base ?: $tax_obj->name );

			$paths[] = preg_quote( $rest_path, '/' );
			$paths[] = preg_quote( $rest_path . '/' . $term_obj->term_id, '/' ) . '.*';
		}

		return $paths;
	}

	/**
	 * Determine path(s) for blog index purging
	 *
	 * @param object $post the post being purged
	 *
	 * @return array<int,string>
	 */
	protected function get_blog_index_purge_paths( object $post ): array {
		// Older than a week - unlikely still to be front page of index
		if ( time() - strtotime( $post->post_date_gmt ) > WEEK_IN_SECONDS ) {
			return [];
		}

		if ( 'posts' === get_option( 'show_on_front' ) ) {
			$path = wp_parse_url( home_url(), PHP_URL_PATH );
			$path = preg_quote( $path, '/' ) . '$';
			return [ $path, '\/feed' ];
		}

		$page_for_posts = absint( get_option( 'page_for_posts' ) );
		$page_for_posts = get_permalink( $page_for_posts );
		$path_for_posts = wp_parse_url( $page_for_posts, PHP_URL_PATH );
		$path_for_posts = preg_quote( $path_for_posts, '/' ) . '.*';

		return [ $path_for_posts, '\/feed' ];
	}

	/**
	 * Send HTTP requests to Varnish for cache purging
	 *
	 * @param int|null          $post_id ID of the post being purged
	 * @param object|null       $post    the post object
	 * @param array<int,string> $paths   list of paths to purge
	 *
	 * @return bool
	 */
	protected function send_purge_requests( ?int $post_id = null, ?object $post = null, array $paths = [] ): bool {
		if ( ! class_exists( 'WpeCommon' ) ) {
			return false;
		}

		$path_regex = '.*';
		if ( $paths ) {
			$path_regex = '(' . implode( '|', $paths ) . ')';
		}

		// Chunk paths to purge, as Varnish can only handle so many in a single request.
		$hostname      = $purge_domains[0] ?? null;
		$purge_domains = $this->get_purge_domains( $post_id, $post );
		$purge_chunks  = array_chunk( $purge_domains, 100 );

		foreach ( $purge_chunks as $chunk ) {
			$host_regex  = '^(' . implode( '|', $chunk ) . ')$';
			$req_headers = [
				'X-Purge-Host' => $host_regex,
				'X-Purge-Path' => $path_regex,
			];

			$response = WpeCommon::http_to_varnish( 'PURGE', $hostname, $req_headers );

			if ( ! is_wp_error( $response ) ) {
				continue;
			}

			foreach ( $response->get_error_messages() as $error ) {
				// phpcs:ignore Squiz.PHP.DiscouragedFunctions.Found,WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'ERROR: ' . $error );
			}
		}

		return true;
	}

}
