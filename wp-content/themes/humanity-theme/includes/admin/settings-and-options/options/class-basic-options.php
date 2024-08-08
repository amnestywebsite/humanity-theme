<?php

declare( strict_types = 1 );

namespace Amnesty\Options;

new Basic_Options();

/**
 * Register the basic options section
 */
class Basic_Options extends Section {

	/**
	 * The ID of the options section
	 *
	 * @var string
	 */
	public static string $section_id = 'basic';

	/**
	 * Retrieve the section title
	 *
	 * @return string
	 */
	public function get_label(): string {
		// translators: [admin]
		return __( 'General', 'amnesty' );
	}

	/**
	 * Render the section
	 *
	 * @return void
	 */
	public function render(): void {
		echo 'general';
	}

	/**
	 * Register the settings for the section
	 *
	 * @param string $option_key the registered option key
	 *
	 * @return void
	 */
	public function register_settings( string $option_key ): void {
		$options = get_option( $option_key );

		$this->register_header_settings( $option_key, $options );
		$this->register_social_settings( $option_key, $options );
	}

	/**
	 * Register the settings for the section
	 *
	 * @param string             $option_key the registered option key
	 * @param array<mixed>|false $options    the stored option values
	 *
	 * @return void
	 */
	protected function register_header_settings( string $option_key, array|false $options ): void {
		// thumbnail; [ image/gif, image/png, image/jpeg, image/svg+xml ]
		add_settings_field( '_site_logotype', /* translators: [admin] */ __( 'Site Logotype', 'amnesty' ), fn () => print 'logotype', $option_key, 'basic' );
		add_settings_field( '_site_logomark', /* translators: [admin] */ __( 'Site Logomark', 'amnesty' ), fn () => print 'logomark', $option_key, 'basic' );

		$logo_callback = $this->create_render_url_field( '_header_logo_link', sprintf( '%s[%s]', $option_key, '_header_logo_link' ), $options['_header_logo_link'] ?? '' );
		add_settings_field( '_header_logo_link', /* translators: [admin] */ __( 'Logo Link', 'amnesty' ), $logo_callback, $option_key, 'basic' );
	}

	/**
	 * Register the settings for the section
	 *
	 * @param string             $option_key the registered option key
	 * @param array<mixed>|false $options    the stored option values
	 *
	 * @return void
	 */
	protected function register_social_settings( string $option_key, array|false $options ): void {
		$title_callback = $this->create_render_text_field( '_social_title', sprintf( '%s[%s]', $option_key, '_social_title' ), $options['_social_title'] ?? '' );
		add_settings_field( '_social_title', /* translators: [admin] */ __( 'Social Media', 'amnesty' ), $title_callback, $option_key, 'basic' );

		$facebook_callback = $this->create_render_url_field( '_social_facebook', sprintf( '%s[%s]', $option_key, '_social_facebook' ), $options['_social_facebook'] ?? '' );
		add_settings_field( '_social_facebook', /* translators: [admin] */ __( 'Facebook URL', 'amnesty' ), $facebook_callback, $option_key, 'basic' );

		$twitter_callback = $this->create_render_text_field( '_social_twitter', sprintf( '%s[%s]', $option_key, '_social_twitter' ), $options['_social_twitter'] ?? '' );
		add_settings_field( '_social_twitter', /* translators: [admin] */ __( 'Twitter Handle', 'amnesty' ), $twitter_callback, $option_key, 'basic' );

		$youtube_callback = $this->create_render_url_field( '_social_youtube', sprintf( '%s[%s]', $option_key, '_social_youtube' ), $options['_social_youtube'] ?? '' );
		add_settings_field( '_social_youtube', /* translators: [admin] */ __( 'YouTube URL', 'amnesty' ), $youtube_callback, $option_key, 'basic' );

		$instagram_callback = $this->create_render_text_field( '_social_instagram', sprintf( '%s[%s]', $option_key, '_social_instagram' ), $options['_social_instagram'] ?? '' );
		add_settings_field( '_social_instagram', /* translators: [admin] */ __( 'Instagram Handle', 'amnesty' ), $instagram_callback, $option_key, 'basic' );

		$telegram_callback = $this->create_render_text_field( '_social_telegram', sprintf( '%s[%s]', $option_key, '_social_telegram' ), $options['_social_telegram'] ?? '' );
		add_settings_field( '_social_telegram', /* translators: [admin] */ __( 'Telegram Handle', 'amnesty' ), $telegram_callback, $option_key, 'basic' );
	}

}
