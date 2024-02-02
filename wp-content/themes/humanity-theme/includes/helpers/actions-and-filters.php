<?php

declare( strict_types = 1 );

if ( ! function_exists( 'add_action_once' ) ) {
	/**
	 * Apply and immediately remove an action
	 *
	 * @package Amnesty
	 *
	 * @param string   $action   the filter to bind to
	 * @param callable $callback the callback to execute
	 * @param int      $priority the priority to register with
	 * @param int      $count    number of expected args
	 *
	 * @return true
	 */
	function add_action_once( string $action, callable $callback, int $priority = 10, int $count = 1 ): bool {
		$closure = function ( ...$args ) use ( $action, $callback, $priority, &$closure ) {
			remove_action( $action, $closure, $priority );
			return call_user_func_array( $callback, $args );
		};

		return add_action( $action, $closure, $priority, $count );
	}
}

if ( ! function_exists( 'add_filter_once' ) ) {
	/**
	 * Apply and immediately remove a filter
	 *
	 * @package Amnesty
	 *
	 * @param string   $filter   the filter to bind to
	 * @param callable $callback the callback to execute
	 * @param int      $priority the priority to register with
	 * @param int      $count    number of expected args
	 *
	 * @return true
	 */
	function add_filter_once( string $filter, callable $callback, int $priority = 10, int $count = 1 ): bool {
		$closure = function ( ...$args ) use ( $filter, $callback, $priority, &$closure ) {
			remove_filter( $filter, $closure, $priority );
			return call_user_func_array( $callback, $args );
		};

		return add_filter( $filter, $closure, $priority, $count );
	}
}

if ( ! function_exists( 'bind_param_to_hook' ) ) {
	/**
	 * Bind an additional parameter to a callback
	 *
	 * @package Amnesty
	 *
	 * @param callable $callback the callback to bind
	 * @param mixed    $extra    the extra parameter
	 *
	 * @return Closure
	 */
	function bind_param_to_hook( callable $callback, $extra ): Closure {
		return fn ( ...$params ) => call_user_func_array( $callback, [ ...$params, $extra ] );
	}
}
