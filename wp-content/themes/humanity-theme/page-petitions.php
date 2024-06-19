<?php

/**
 * Template Name: Petition Index
 *
 * @package Amnesty\Templates
 */

get_header();

// phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
$user_signed_petitions = sanitize_text_field( $_COOKIE['amnesty_petitions'] ?? '' ) ?: [];
if ( $user_signed_petitions ) {
	$user_signed_petitions = array_map( 'absint', explode( ',', $user_signed_petitions ) );
}

$petition_slug  = get_option( 'aip_petition_slug' ) ?: 'petition';
$petition_posts = new WP_Query(
	[
		'posts_per_page' => -1,
		'post_type'      => $petition_slug,
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		'tax_query'      => [
			'relation' => 'AND',
			[
				'taxonomy'         => 'visibility',
				'field'            => 'slug',
				'terms'            => 'hidden',
				'include_children' => false,
				'operator'         => 'NOT IN',
			],
		],
	]
);

$all_blocks = parse_blocks( $post->post_content );

$heading_blocks = array_values(
	array_filter(
		$all_blocks,
		fn ( $block ) => 'core/heading' === $block['blockName']
	)
);

$sections = array_values(
	array_filter(
		$all_blocks,
		fn ( $block ) => 'amnesty-core/block-section' === $block['blockName']
	)
);

$opening_section = array_shift( $sections );

?>
<main id="main">
<?php if ( $opening_section ) : ?>
	<section class="section">
		<div class="container has-gutter">
			<?php echo wp_kses_post( amnesty_render_blocks( $opening_section['innerBlocks'] ?? [] ) ); ?>
		</div>
	</section>
<?php endif; ?>

	<section class="section" aria-label="<?php /* translators: [front] */ esc_attr_e( 'All Petitions', 'amnesty' ); ?>">
		<div class="container has-gutter">
			<?php if ( isset( $heading_blocks[0] ) ) : ?>
				<?php echo wp_kses_post( render_block( $heading_blocks[0] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<div class="postlist">
			<?php

			while ( $petition_posts->have_posts() ) {
				$petition_posts->the_post();

				get_template_part( 'partials/petitions/item', null, compact( 'user_signed_petitions' ) );
			}

			wp_reset_postdata();

			?>
			</div>
		</div>
	</section>
	<?php

	foreach ( $sections as $section ) {
		echo '<section class="section">';
		echo '<div class="container has-gutter">';
		echo amnesty_render_blocks( $section['innerBlocks'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		echo '</section>';
	}

	?>
</main>
<?php get_footer(); ?>
