<?php

/**
 * Global partial, petitions container
 *
 * @package Amnesty\Partials
 */

?>
<section class="article has-sidebar">
	<header class="article-header is-narrow">
		<h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
	</header>

	<article class="article-content is-narrow" aria-labelledby="article-title">
	<?php echo do_blocks( $content ); // phpcs:ignore ?>
	</article>
</section>
