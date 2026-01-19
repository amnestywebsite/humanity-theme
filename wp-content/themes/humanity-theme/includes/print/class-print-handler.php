<?php

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

declare( strict_types = 1 );

namespace Amnesty;

use DOMDocument;
use DOMElement;

new Print_Handler();

/**
 * Handles content modifications for a better print experience
 */
class Print_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'amnesty_the_content', [ $this, 'list_links' ] );
	}

	/**
	 * Extract all links from the content, and append as a list
	 *
	 * Add references to each link, so that the printer may
	 * associate the link with the text within the content.
	 *
	 * @param string $content the entity's content
	 *
	 * @return string
	 */
	public function list_links( string $content ): string {
		if ( ! is_single() ) {
			return $content;
		}

		if ( ! $content ) {
			return $content;
		}

		$cache_key = __FUNCTION__ . hash( 'xxh3', $content );
		$cached    = get_transient( $cache_key );

		if ( is_string( $cached ) ) {
			return $cached;
		}

		$dom = $this->create_dom( $content );

		$links = $dom->getElementsByTagName( 'a' );

		$num_links = count( $links );

		if ( ! $num_links ) {
			return $content;
		}

		$container = $dom->createElement( 'div' );

		if ( ! $container ) {
			return $content;
		}

		$container->setAttribute( 'class', 'content-links print-only' );

		$list = $dom->createElement( 'ol' );

		if ( ! $list ) {
			return $content;
		}

		$list->setAttribute( 'style', 'list-style-type: upper-latin;' );

		$index = 0;

		// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		/** @var \DOMElement $link */
		foreach ( $links->getIterator() as $link ) {
			$id = $this->get_id( $index );

			$this->build_list_item( $dom, $list, $link );
			$this->build_note( $dom, $link, $id );

			++$index;
		}

		$heading = $dom->createElement( 'h2' );
		if ( $heading ) {
			$heading->textContent = __( 'Content links', 'amnesty' );
			$container->appendChild( $heading );
		}

		$container->appendChild( $list );
		$dom->appendChild( $container );

		$new_content = (string) $dom->saveHTML();

		set_transient( $cache_key, $new_content, DAY_IN_SECONDS );

		return $new_content;
	}

	/**
	 * Create a DOMDocument object for the content
	 *
	 * @param string $content the content to load
	 *
	 * @return \DOMDocument
	 */
	protected function create_dom( string $content ): DOMDocument {
		$dom = new DOMDocument( '1.0', 'utf-8' );

		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$dom->formatOutput       = false;
		$dom->substituteEntities = false;
		$dom->preserveWhiteSpace = true;
		$dom->validateOnParse    = false;

		// ensure string is utf8
		$encoded_content = mb_convert_encoding( $content, 'UTF-8' );
		// encode everything
		$encoded_content = htmlentities( (string) $encoded_content, ENT_NOQUOTES, 'UTF-8' );
		// decode "standard" characters
		$encoded_content = htmlspecialchars_decode( $encoded_content, ENT_NOQUOTES );
		// convert left side of ISO-8859-1 to HTML numeric character reference
		// this was taken from PHP docs for mb_encode_numericentity   vvvvvvvvvvvvvvvvvvvvvvvvv
		$encoded_content = mb_encode_numericentity( $encoded_content, [ 0x80, 0x10FFFF, 0, ~0 ], 'UTF-8' );

		libxml_use_internal_errors( true );
		$dom->loadHTML(
			$encoded_content,
			LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NOENT
		);
		libxml_use_internal_errors( false );

		return $dom;
	}

	/**
	 * Retrieve list item identifier from list index
	 *
	 * @param int $index the link index
	 *
	 * @return string
	 */
	protected function get_id( int $index ): string {
		$id = '';

		if ( $index >= 26 ) {
			$id .= chr( (int) ( intdiv( $index, 26 ) / 26 ) + 65 );
		}

		$id .= chr( ( $index % 26 ) + 65 );

		return $id;
	}

	/**
	 * Build the DOM tree for the list item
	 *
	 * @param \DOMDocument $dom  the DOM tree
	 * @param \DOMElement  $ol   the list element
	 * @param \DOMElement  $item the link element
	 *
	 * @return void
	 */
	protected function build_list_item( DOMDocument $dom, DOMElement $ol, DOMElement $item ): void {
		$li = $dom->createElement( 'li' );

		if ( ! $li ) {
			return;
		}

		$strong = $dom->createElement( 'strong' );

		if ( $strong ) {
			$strong->textContent = $item->textContent;
			$li->appendChild( $strong );
		} else {
			$li->textContent = $item->textContent;
		}

		$li->textContent .= ' ';

		$em = $dom->createElement( 'em' );

		if ( $em ) {
			$em->textContent = $item->getAttribute( 'href' );
			$li->appendChild( $em );
		} else {
			$li->textContent .= $item->getAttribute( 'href' );
		}

		$ol->appendChild( $li );
	}

	/**
	 * Build the DOM tree for the reference
	 *
	 * @param \DOMDocument $dom  the DOM tree
	 * @param \DOMElement  $item the link element
	 * @param string       $id   the link identifier
	 *
	 * @return void
	 */
	protected function build_note( DOMDocument $dom, DOMElement $item, string $id ): void {
		$note = $dom->createElement( 'sup' );

		if ( ! $note ) {
			return;
		}

		$note->setAttribute( 'class', 'print-reference print-only' );
		$note->textContent = "[{$id}]";
		$item->appendChild( $note );
	}

}

// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
