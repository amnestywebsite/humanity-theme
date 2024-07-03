<?php

/**
 * Post type archive template
 *
 * @package Amnesty\Templates
 */

get_header();

$network_options = get_site_option( 'amnesty_network_options' );
$filter_type     = $network_options['enabled_features'][0]['filter-type'] ?? 'categories';

$current_term = get_queried_object();
$slider_items = [];

$term_name        = $current_term->name ?? '';
$term_description = $current_term->description ?? '';

if ( is_a( $current_term, 'WP_Term' ) && 'category' === $current_term->taxonomy ) {
	$slider_items = get_archive_slider_posts( $current_term );
}

?>

<main id="main">
<?php if ( is_home() && get_option( 'page_for_posts' ) ) : ?>
	<?php echo wp_kses( apply_filters( 'the_content', get_post_field( 'post_content', get_option( 'page_for_posts' ) ) ), 'slider' ); ?>
<?php endif; ?>

	<div class="container">
	<?php

	if ( $slider_items ) {
		$slider_data = [
			'sliderId'             => 'categoryslider',
			'timelineCaptionStyle' => 'dark',
			'slides'               => $slider_items,
			'hasContent'           => true,
			'hasArrows'            => true,
			'showTabs'             => true,
			'hideContent'          => false,
		];

		print '<div class="archive-slider">';
		echo wp_kses( amnesty_render_block_slider( $slider_data ), 'slider' );
		print '</div>';
	}

	?>
	</div>
	<div class="container has-gutter">
	<?php

	if ( '' != $term_name && '' != $term_description ) {
		print '<div class="categoryTerm-title">';
		printf( '<h1>%1$s</h1>', esc_html( $term_name ) );
		printf( '<p>%1$s</p>', wp_kses_post( $term_description ) );
		print '</div>';
	}

	if ( 'categories' === $filter_type ) {
		get_template_part( 'partials/archive/categories' );
	} else {
		get_template_part( 'partials/archive/filters' );
		get_template_part( 'partials/archive/filters-active' );
	}

	?>
	</div>

	<div class="container has-gutter">
		<section class="news-section section section--small section--dark" aria-label="<?php /* translators: [front] ARIA https://www.amnesty.eu/news/ a number followed by the string on the list of posts */ esc_attr_e( 'Results', 'amnesty' ); ?>">
			<?php get_template_part( 'partials/archive/header' ); ?>
			<div class="postlist">
			<?php
			while ( have_posts() ) {
				the_post();
				$has_featured = amnesty_featured_image();

				get_template_part( 'partials/post/post', $has_featured ? 'small' : 'none' );
			}
			?>
			</div>
		</section>
	</div>

	<div class="container has-gutter">
	<?php get_template_part( 'partials/pagination' ); ?>
	</div>
</main>
<?php get_footer(); ?>
