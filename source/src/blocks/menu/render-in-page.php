<?php

$blocks   = parse_blocks( get_post_field( 'post_content' ) );
$sections = array_filter(
	$blocks,
	// phpcs:ignore Universal.FunctionDeclarations.NoLongClosures.ExceedsRecommended
	function ( $block ) {
		if ( 'amnesty-core/block-section' !== $block['blockName'] ) {
			return false;
		}

		if ( empty( $block['attrs']['sectionId'] ) || empty( $block['attrs']['sectionName'] ) ) {
			return false;
		}

		return true;
	}
);

if ( empty( $sections ) ) {
	return;
}

?>
<section class="postlist-categoriesContainer <?php empty( $attributes['color'] ) || printf( 'section--%s', esc_attr( $attributes['color'] ) ); ?>" data-slider>
	<nav>
		<ul class="postlist-categories<?php count( $sections ) > 4 && print ' use-flickity'; ?>" aria-label="<?php /* translators: [front] ARIA https://isaidotorgstg.wpengine.com/en/latest/ */ esc_attr_e( 'List of page sections', 'amnesty' ); ?>">
		<?php

		foreach ( $sections as $section ) {
			printf( '<li><a class="btn btn--white" href="#%s">%s</a></li>', esc_attr( $section['attrs']['sectionId'] ), esc_html( $section['attrs']['sectionName'] ) );
		}

		?>
		</ul>
	</nav>
	<button data-slider-prev disabled><?php esc_html_e( 'Previous', 'amnesty' ); ?></button>
	<button data-slider-next><?php esc_html_e( 'Next', 'amnesty' ); ?></button>
</section>
