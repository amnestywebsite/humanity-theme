<?php

declare( strict_types = 1 );

// phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh, Universal.Files.SeparateFunctionsFromOO.Mixed

if ( ! function_exists( 'classnames' ) ) {
	/**
	 * Class name builder helper
	 *
	 * @package Amnesty
	 *
	 * @param array ...$args any supplied arguments
	 *
	 * @return string
	 */
	function classnames( ...$args ) {
		return ( new Classnames( ...$args ) )->get();
	}
}

/**
 * Replicate functionality of the `classnames` JS package
 *
 * @package Amnesty
 */
class Classnames {

	/**
	 * Raw arguments
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Dirty class list
	 *
	 * @var array
	 */
	protected $dirty = [];

	/**
	 * Cleaned class list
	 *
	 * @var array
	 */
	protected $clean = [];

	/**
	 * Execute processing
	 *
	 * @param array ...$args any supplied arguments
	 */
	public function __construct( ...$args ) {
		$this->args = $args;
		$this->run();
	}

	/**
	 * Output class list directly
	 *
	 * @return void
	 */
	public function echo() {
		echo esc_attr( $this->get() );
	}

	/**
	 * Return final class list
	 *
	 * @return string
	 */
	public function get() {
		return implode( ' ', $this->clean );
	}

	/**
	 * Process passed args
	 *
	 * @return void
	 */
	protected function run() {
		foreach ( $this->args as $item ) {
			$this->run_item( $item );
		}

		foreach ( $this->dirty as $key => $val ) {
			if ( $this->key_is( $key, $val ) ) {
				$this->clean[] = $key;
				continue;
			}

			if ( $this->val_is( $key, $val ) ) {
				$this->clean[] = $val;
				continue;
			}
		}

		$this->clean = array_filter( $this->clean );
	}

	/**
	 * Process a single argument
	 *
	 * @param mixed $item a single supplied argument
	 *
	 * @return void
	 */
	protected function run_item( $item ) {
		switch ( gettype( $item ) ) {
			case 'string':
				$this->dirty[] = $item;
				break;

			case 'array':
				$this->dirty = array_merge( $this->dirty, $item );
				break;

			case 'object':
				$this->dirty = array_merge( $this->dirty, $this->to_array( $item ) );
				break;

			case 'boolean':
			case 'NULL':
			case 'resource':
			case 'resource (closed)':
				break;

			case 'integer':
			case 'double':
			case 'unknown type':
				if ( (bool) $item && is_scalar( (string) $item ) ) {
					$this->dirty[] = (string) $item;
				}
				break;

			default:
				break;
		}
	}

	/**
	 * Convert an object to an array
	 *
	 * @param mixed $the_object an object-type item
	 *
	 * @return array
	 */
	protected function to_array( $the_object ) {
		if ( method_exists( $the_object, 'to_array' ) ) {
			return $the_object->to_array();
		}

		if ( method_exists( $the_object, 'toArray' ) ) {
			return $the_object->toArray();
		}

		return get_object_vars( $the_object );
	}

	/**
	 * Check that an item's key is the class name to use
	 *
	 * @param mixed $key the current item's key
	 * @param mixed $val the current item's value
	 *
	 * @return bool
	 */
	protected function key_is( $key, $val ) {
		return is_string( $key ) && (bool) $key && (bool) $val;
	}

	/**
	 * Check that an item's value is the class name to use
	 *
	 * @param mixed $key the current item's key
	 * @param mixed $val the current item's value
	 *
	 * @return bool
	 */
	protected function val_is( $key, $val ) {
		return ! is_string( $key ) && is_string( $val ) && (bool) $val;
	}

}
