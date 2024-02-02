<?php

/**
 * Archives partial, categories
 *
 * @package Amnesty\Partials
 */

$featured_categories = get_terms(
	[
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'parent'     => 0,
	]
);

$featured_categories = array_values( $featured_categories );
$current_category    = false;

if ( is_category() ) {
	$current_category = get_queried_object();
}

?>

<?php if ( $featured_categories ) : ?>
<section class="section section--small section--dark postlist-categoriesContainer" style="display: flex;" data-slider>
	<ul class="postlist-categories<?php count( $featured_categories ) > 4 && print ' use-flickity'; ?>" style="flex: 1 1 100%" aria-label="<?php /* translators: [front] */ esc_attr_e( 'Filter results by category', 'amnesty' ); ?>">
	<?php
	foreach ( $featured_categories as $key => $featured_category ) :
		$active = false;
		if ( isset( $current_category->term_id ) ) {
			$active = determine_if_term_is_parent( $current_category->term_id, $featured_category->term_id );
		}

		$termlink = get_term_link( $featured_category );

		if ( is_wp_error( $termlink ) ) {
			$termlink = home_url();
		}
		?>
		<li <?php $active && printf( 'class="is-current" data-categories-selected="%d"', esc_attr( $key ) ); ?>>
			<div>
				<a class="btn btn--white" href="<?php echo esc_url( $termlink ); ?>"><?php echo esc_html( $featured_category->name ); ?></a>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<button data-slider-prev disabled><?php /* translators: [front] */ esc_html_e( 'Previous', 'amnesty' ); ?></button>
	<button data-slider-next><?php /* translators: [front] */ esc_html_e( 'Next', 'amnesty' ); ?></button>
</section>
<?php endif; ?>

<?php

get_template_part( 'partials/archive/subcategories' );
