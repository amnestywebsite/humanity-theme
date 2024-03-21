<?php

declare( strict_types = 1 );

namespace Amnesty;

use MO;

/**
 * Retrieve a list of sites with localised language names
 *
 * @package Amnesty\RestApi
 */
class Core_Site_List {

	/**
	 * Class instance reference
	 *
	 * @var \MO
	 */
	protected $mo = null;

	/**
	 * List of sites on network
	 *
	 * @var array
	 */
	protected $sites = [];

	/**
	 * The current site
	 *
	 * @var integer
	 */
	protected $current = 1;

	/**
	 * Construct the instance variables
	 */
	public function __construct() {
		$this->mo      = new MO();
		$this->sites   = get_sites();
		$this->current = get_current_blog_id();
	}

	/**
	 * Retrieve required info for rendering the language selector
	 *
	 * @return array
	 */
	public function get_sites() {
		$sites = [];

		foreach ( $this->sites as $site ) {
			$blog_id = intval( $site->blog_id, 10 );

			switch_to_blog( $blog_id );

			$sites[] = (object) [
				'lang'      => get_site_language_name( $blog_id ),
				'code'      => get_site_language_code( $blog_id ),
				'direction' => $this->get_direction( $blog_id ),
				'name'      => get_bloginfo( 'name' ),
				'url'       => home_url( '/', 'https' ),
				'path'      => get_site( $blog_id )->path,
				'current'   => $this->current === $blog_id,
				'site_id'   => $blog_id,
				'post_id'   => intval( get_option( 'page_on_front', '0' ), 10 ),
			];

			restore_current_blog();
		}

		return $sites;
	}

	/**
	 * Retrieve the current locale
	 *
	 * @param int $blog_id the blog context
	 *
	 * @return string
	 */
	protected function get_locale( $blog_id = 0 ) {
		return get_blog_option( $blog_id, 'WPLANG' ) ?: get_site_option( 'WPLANG' ) ?: $GLOBALS['wp_local_package'] ?: 'en_GB';
	}

	/**
	 * Retrieve text direction for specified language.
	 *
	 * @param int $blog_id the blog context
	 *
	 * @return string
	 */
	protected function get_direction( $blog_id = 0 ) {
		$direction = get_blog_option( $blog_id, 'amnesty_text_direction' );
		if ( $direction && in_array( $direction, [ 'ltr', 'rtl' ], true ) ) {
			return $direction;
		}

		$lang      = $this->get_locale( $blog_id );
		$direction = 'ltr';

		$file = sprintf( '%s/languages/%s.mo', get_stylesheet_directory(), $lang );

		if ( ! is_readable( $file ) ) {
			return $direction;
		}

		$this->mo->import_from_file( $file );

		$direction = preg_replace( '/[^ltr]/u', '', $this->mo->translate( 'ltr', 'text direction' ) );

		add_blog_option( $blog_id, 'amnesty_text_direction', $direction );

		return $direction;
	}

}
