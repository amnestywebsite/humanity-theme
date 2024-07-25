<?php

/**
 * Single template
 *
 * @package Amnesty\Templates
 */

get_header();
the_post();

$max_post_content    = get_post_meta( get_the_ID(), '_maximize_post_content', true );
$article_has_sidebar = empty( $max_post_content ) ? 'has-sidebar' : '';

?>

<main id="main">
	<?php
	if ( ! amnesty_post_has_header() ) {
		get_template_part( 'partials/single/featured-image' );
	}
	?>

	<div class="container has-gutter">
		<div class="article-container">
			<section class="article <?php echo esc_attr( $article_has_sidebar ); ?>">
				<header class="article-header">
					<?php get_template_part( 'partials/single/meta-area' ); ?>
					<h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
				</header>

				<article class="article-content" aria-labelledby="article-title">
					<?php amnesty_the_content(); ?>
				</article>

				<footer class="article-footer">
					<?php get_template_part( 'partials/single/term-area' ); ?>
				</footer>
			</section>

		<?php if ( empty( $max_post_content ) ) : ?>
			<aside class="article-sidebar" aria-label="<?php /* translators: [front] https://wordpresstheme.amnesty.org/the-theme/global-elements/m004-social-share/ */ echo esc_attr( _x( 'Sidebar', 'Post Type Singular Name', 'amnesty' ) ); ?>">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
