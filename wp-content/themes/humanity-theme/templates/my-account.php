<?php

/**
 * Template Name: Account Management
 *
 * @package Amnesty\Templates
 */

get_header();
the_post();

?>
<main id="main">
	<section class="section section--small">
		<div class="container container--small article-container">
			<article class="article">
				<header class="article-header">
					<h1 class="article-title"><?php the_title(); ?></h1>
				</header>
				<section class="article-content">
					<?php the_content(); ?>
				</section>
			</article>
		</div>
	</section>
</main>
<?php

get_footer();
