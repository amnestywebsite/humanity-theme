<?php

/**
 * Page template
 *
 * @package Amnesty\Templates
 */

get_header();
the_post();

$sidebar_is_enabled = amnesty_get_meta_field( '_disable_sidebar' ) !== '1';
$hero_title         = amnesty_get_meta_field( '_hero_title' );

if ( amnesty_post_has_hero() ) {
	$hero_data  = amnesty_get_hero_data();
	$hero_title = $hero_data['attrs']['title'] ?? false;
}

?>

<main id="main">
	<section class="section section--small">
		<div class="container article-container has-gutter">
			<article class="article <?php $sidebar_is_enabled && print 'has-sidebar'; ?>">
			<?php if ( ! $hero_title ) : ?>
				<header class="article-header">
					<h1 class="article-title"><?php the_title(); ?></h1>
				</header>
			<?php endif; ?>
				<section class="article-content">
					<?php the_content(); ?>
				</section>
			</article>

		<?php if ( $sidebar_is_enabled ) : ?>
			<aside class="article-sidebar">
				<?php get_sidebar(); ?>
			</aside>
		<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
