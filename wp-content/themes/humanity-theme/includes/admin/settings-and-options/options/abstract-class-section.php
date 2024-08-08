<?php

declare( strict_types = 1 );

namespace Amnesty\Options;

/**
 * The base class for registering options sections
 */
abstract class Section {

	/**
	 * The ID of the options section
	 *
	 * @var string
	 */
	public static string $section_id;

	/**
	 * Construct the section
	 */
	public function __construct() {
		add_action( 'amnesty_register_settings_sections', [ $this, 'register_section' ] );
	}

	/**
	 * Register the section
	 *
	 * @param string $option_key the registered option key
	 *
	 * @return void
	 */
	public function register_section( string $option_key ): void {
		add_settings_section( static::$section_id, $this->get_label(), [ $this, 'render' ], $option_key );
		$this->register_settings( $option_key );
	}

	/**
	 * Render the section
	 *
	 * @return void
	 */
	abstract public function render(): void;

	/**
	 * Retrieve the section title
	 *
	 * @return string
	 */
	abstract public function get_label(): string;

	/**
	 * Register the settings for the section
	 *
	 * @param string $option_key the registered option key
	 *
	 * @return void
	 */
	abstract public function register_settings( string $option_key ): void;

	/**
	 * Create a render function for a text settings field
	 *
	 * @param string $id    the input id
	 * @param string $name  the input name
	 * @param string $value the current input value
	 *
	 * @return callable
	 */
	public function create_render_text_field( string $id, string $name, string $value ): callable {
		return fn () => printf( '<input id="%1$s" name="%2$s" type="text" value="%3$s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
	}

	/**
	 * Create a render function for a url settings field
	 *
	 * @param string $id    the input id
	 * @param string $name  the input name
	 * @param string $value the current input value
	 *
	 * @return callable
	 */
	public function create_render_url_field( string $id, string $name, string $value ): callable {
		return fn () => printf( '<input id="%1$s" name="%2$s" type="text" value="%3$s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ) );
	}

}
