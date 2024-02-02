<?php

declare( strict_types = 1 );

if ( ! function_exists( 'register_petition_list_block' ) ) {
	/**
	 * Register the Petition List block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @return void
	 */
	function register_petition_list_block(): void {
		$all_types = amnesty_get_post_types();
		$petition  = get_option( 'aip_petition_slug' ) ?: 'petition';

		if ( ! isset( $all_types[ $petition ] ) ) {
			return;
		}

		$default_petition            = new stdClass();
		$default_petition->name      = $all_types['petition'];
		$default_petition->rest_base = $all_types['petition'];

		register_block_type(
			'amnesty-core/petition-list',
			[
				'render_callback' => 'amnesty_render_petition_list_block',
				'editor_script'   => 'amnesty-core-blocks-js',
				'attributes'      => [
					'style'           => [
						'default' => 'petition',
						'type'    => 'string',
					],
					'displayAuthor'   => [
						'type'    => 'boolean',
						'default' => false,
					],
					'displayPostDate' => [
						'type'    => 'boolean',
						'default' => false,
					],
					'postTypes'       => [
						'type'    => 'object',
						'default' => $default_petition,
					],
				],
			] 
		);
	}
}
