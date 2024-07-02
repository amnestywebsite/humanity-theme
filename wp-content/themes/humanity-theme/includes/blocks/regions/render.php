<?php

declare( strict_types = 1 );

if ( ! function_exists( 'amnesty_render_regions_block' ) ) {
	/**
	 * Render the Regions block - a visual representation of a term hierarchy
	 *
	 * @package Amnesty\Blocks
	 *
	 * @param array $attributes the block's attributes
	 *
	 * @return string
	 */
	function amnesty_render_regions_block( array $attributes = [] ) {
		$args = wp_parse_args(
			$attributes,
			[
				/* translators: [front] Deafult text can be changed in CMS for https://isaidotorgstg.wpengine.com/en/countries/  https://wordpresstheme.amnesty.org/blocks/b026-regions-list-block/ */
				'title'       => __( 'Explore by Region', 'amnesty' ),
				'taxonomy'    => '',
				'background'  => '',
				'alignment'   => '',
				'depth'       => 1,
				'regionsOnly' => false,
			]
		);

		if ( ! taxonomy_exists( $args['taxonomy'] ) ) {
			return '';
		}

		$query_args = [
			'depth'              => absint( $args['depth'] ) + 1,
			'hide_empty'         => false,
			'taxonomy'           => $args['taxonomy'],
			'title_li'           => false,
			'show_option_none'   => false,
			'use_desc_for_title' => false,
		];

		if ( $args['regionsOnly'] ) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$query_args['meta_query'] = [
				[
					'key'     => 'type',
					'value'   => [ 'region', 'subregion' ],
					'compare' => 'IN',
				],
			];
		}

		$title_id      = sanitize_title_with_dashes( $args['title'] );
		$title_classes = classnames( [ "is-{$args['alignment']}-aligned" => (bool) $args['alignment'] ] );
		$wrap_classes  = classnames(
			'wp-block-amnesty-core-regions',
			[
				"has-{$args['background']}-background-color" => (bool) $args['background'],
			]
		);

		spaceless();

		printf( '<aside class="%s" aria-labelledby="%s">', esc_attr( $wrap_classes ), esc_attr( $title_id ) );
		printf( '<h2 id="%s" class="%s">%s</h2>', esc_attr( $title_id ), esc_attr( $title_classes ), esc_html( $args['title'] ) );
		/* translators: [front] https://isaidotorgstg.wpengine.com/en/countries/  https://wordpresstheme.amnesty.org/blocks/b026-regions-list-block/  */
		printf( '<ul class="listItems" aria-label="%s">', esc_html__( 'Hierarchical list of terms', 'amnesty' ) );

		add_filter(
			'category_css_class',
			function ( array $classes, WP_Term $term, int $depth, array $args = [] ) {
				if ( isset( $args['has_children'] ) && $args['has_children'] ) {

					$classes[] = 'has-children';
				}
				return $classes;
			},
			10,
			4
		);

		wp_list_categories( $query_args );

		echo '</ul>';
		echo '</aside>';

		return endspaceless( false );
	}
}
