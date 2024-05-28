<?php

/**
 * Search results template
 *
 * @package Amnesty\Templates
 */

get_header();

?>
<main id="main">
	<div class="container search-container has-gutter">
		<?php get_template_part( 'partials/search/horizontal-search' ); ?>

		<section class="section search-results section--tinted" aria-label="<?php /* translators: [front] ARIA */ esc_attr_e( 'Search results', 'amnesty' ); ?>">
		<?php

		get_template_part( 'partials/archive/header' );

		do_action( 'amnesty_before_search_results' );

		while ( have_posts() ) {
			the_post();
			get_template_part( 'partials/post/post', 'search' );
		}

		do_action( 'amnesty_after_search_results' );

		?>
		</section>
	</div>

	<div class="container container--small has-gutter">
		<?php get_template_part( 'partials/pagination' ); ?>
	</div>

</main>
<?php get_footer(); ?>
