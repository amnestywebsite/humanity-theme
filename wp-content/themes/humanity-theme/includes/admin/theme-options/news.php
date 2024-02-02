<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_register_theme_options_news' ) ) {
	/**
	 * Register theme options for News
	 *
	 * @package Amnesty\ThemeOptions
	 *
	 * @param CMB2 $options the options object
	 *
	 * @return void
	 */
	function amnesty_register_theme_options_news( CMB2 $options ): void {
		$options->add_field(
			[
				/* translators: [admin] */
				'name' => __( 'News', 'amnesty' ),
				'id'   => '_news_title',
				'type' => 'title',
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'    => __( 'Default Post sidebar', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Drag posts from the left column to the right column to attach them to this page.<br />Only the first sidebar selected will be used.', 'amnesty' ),
				'id'      => '_default_sidebar',
				'type'    => 'custom_attached_posts',
				'column'  => true,
				'options' => [
					'show_thumbnails' => true,
					'filter_boxes'    => true,
					'query_args'      => [
						'posts_per_page' => 10,
						'post_type'      => 'sidebar',
					],
				],
			]
		);

		$options->add_field(
			[
				/* translators: [admin] */
				'name'    => __( 'Default Page sidebar', 'amnesty' ),
				/* translators: [admin] */
				'desc'    => __( 'Drag posts from the left column to the right column to attach them to this page.<br />Only the first sidebar selected will be used.', 'amnesty' ),
				'id'      => '_default_sidebar_page',
				'type'    => 'custom_attached_posts',
				'column'  => true,
				'options' => [
					'show_thumbnails' => true,
					'filter_boxes'    => true,
					'query_args'      => [
						'posts_per_page' => 10,
						'post_type'      => 'sidebar',
					],
				],
			]
		);
	}
}

add_action( 'amnesty_register_theme_options', 'amnesty_register_theme_options_news' );
