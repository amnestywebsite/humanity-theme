<?php

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

if ( ! taxonomy_exists( $attributes['taxonomy'] ) ) {
	return;
}

$cache_key = md5( sprintf( '%s:%s', __FILE__, $attributes['taxonomy'] ) );
$terms     = wp_cache_get( $cache_key );

if ( ! $terms ) {
	$terms = amnesty_term_list_block_get_terms( $attributes['taxonomy'] );
	wp_cache_add( $cache_key, $terms );
}

$groups = group_terms_by_initial_ascii_letter( $terms );

foreach ( $groups as $key => &$terms ) {
	usort(
		$terms,
		fn ( WP_Term $a, WP_Term $b ): int => remove_accents( $a->name ) <=> remove_accents( $b->name )
	);
}

$letters = array_keys( $groups );
$first   = $letters[0];

?>
<aside class="wp-block-amnesty-core-term-list">
	<h2 class="<?php echo esc_attr( $attributes['alignment'] ? sprintf( 'u-text%s', ucfirst( $attributes['alignment'] ) ) : '' ); ?>"><?php echo esc_html( $attributes['title'] ); ?></h2>
	<div class="navigation">
	<?php foreach ( $letters as $letter ) : ?>
		<button class="<?php echo esc_attr( $first === $letter ? 'is-active' : '' ); ?>" <?php disabled( empty( $groups[ $letter ] ) ); ?>><?php echo esc_html( $letter ); ?></button>
	<?php endforeach; ?>
	</div>
	<div class="listContainer">
	<div class="activeLetter"><?php echo esc_html( $first ); ?></div>
	<?php foreach ( $letters as $letter ) : ?>
		<?php if ( empty( $groups[ $letter ] ) ) continue; // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed ?>
		<ul class="listItems" data-key="<?php echo esc_attr( $letter ); ?>" style="display:<?php echo esc_attr( $first === $letter ? 'flex' : 'none' ); ?>">
		<?php foreach ( $groups[ $letter ] as $_term ) : ?>
			<li class="listItem">
				<a href="<?php echo esc_url( amnesty_term_link( $_term ) ); ?>"><?php echo esc_html( $_term->name ); ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	</div>
</aside>
