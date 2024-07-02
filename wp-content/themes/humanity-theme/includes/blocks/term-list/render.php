<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_term_list_block_get_terms' ) ) {
	/**
	 * Retrieve terms for the term list block
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param string $taxonomy the taxonomy name
	 *
	 * @return array<int,WP_Term>
	 */
	function amnesty_term_list_block_get_terms( string $taxonomy ) {
		return get_terms(
			[
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'meta_query' => [
					'relation' => 'AND',
					[
						'relation' => 'OR',
						[
							'key'     => 'type',
							'value'   => 'default',
							'compare' => '=',
						],
						[
							'key'     => 'type',
							'compare' => 'NOT EXISTS',
						],
					],
				],
			]
		);
	}
}

if ( ! function_exists( 'amnesty_render_term_list_block' ) ) {
	/**
	 * Render the Term List block - an A-Z of terms in a taxonomy
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block's attributes
	 *
	 * @return string
	 */
	function amnesty_render_term_list_block( array $attributes = [] ) {
		$args = wp_parse_args(
			$attributes,
			[
				/* translators: [front] default for https://wordpresstheme.amnesty.org/blocks/b028-term-a-z/ editable in CMS */
				'title'     => __( 'A-Z of Countries and Regions', 'amnesty' ),
				'taxonomy'  => '',
				'alignment' => '',
			]
		);

		if ( ! taxonomy_exists( $args['taxonomy'] ) ) {
			return '';
		}

		$cache_key = md5( sprintf( '%s:%s', __FUNCTION__, $args['taxonomy'] ) );
		$terms     = wp_cache_get( $cache_key );

		if ( ! $terms ) {
			$terms = amnesty_term_list_block_get_terms( $args['taxonomy'] );
			wp_cache_add( $cache_key, $terms );
		}

		$groups = group_terms_by_initial_ascii_letter( $terms );

		foreach ( $groups as $key => &$terms ) {
			usort(
				$terms,
				fn ( WP_Term $a, WP_Term $b ): int =>
					remove_accents( $a->name ) <=> remove_accents( $b->name )
			);
		}

		$letters = array_keys( $groups );
		$first   = $letters[0]; // used in view

		spaceless();
		require realpath( __DIR__ . '/views/term-list.php' );
		return endspaceless( false );
	}
}
